<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_USER = 1;
    const ROLE_ADMIN = 2;

    public static $roles = [
        self::ROLE_USER => 'User',
        self::ROLE_ADMIN => 'Administrator',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'email', 'role', 'email_verified_at', 'created_at', 'updated_at', 'timezone', 'locale', 'version', 'tester', 'idfa', 'ad_tracking', 'random_string', 'store_country', 'notification_key', 'name', 'gender'
    ];

    public static $api_edit_rules = [
        'timezone' => 'string',
        'locale' => 'string',
        'version' => 'integer',
        'idfa' => 'string|max:255|nullable',
        'ad_tracking' => 'boolean|nullable',
        'random_string' => 'string|max:255',
        'store_country' => 'string|min:2|max:3',
        'notification_key' => 'string|max:255',
    ];

    public static $admin_edit_rules = [
        'timezone' => 'string',
        'locale' => 'string',
        'version' => 'integer',
        'password' => 'confirmed|min:6',
        'idfa' => 'string|max:255|nullable',
        'ad_tracking' => 'boolean|nullable',
        'random_string' => 'nullable|string|max:255',
        'store_country' => 'nullable|string|min:2|max:3',
        'notification_key' => 'nullable|string|max:255',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function appleOriginalTransactions(){
        return $this->hasMany( 'App\AppleOriginalTransaction' );
    }

    public function receipts(){
        return $this->hasMany( 'App\Receipt' );
    }

    public function freeAccess(){
        return $this->hasMany( 'App\FreeAccess' );
    }

    public function authTokens(){
        return $this->hasMany( 'App\AuthToken' );
    }

    public function searchAdsInfo(){
        return $this->belongsToMany( 'App\SearchAdsInfo', 'users_2_search_ads_info' )->whereNotNull( 'iad-campaign-id' )->where( 'iad-campaign-id', '<>', '1234567890' );
    }

    public function appInstalls(){
        return $this->belongsToMany( 'App\AppInstall', 'users_2_app_installs' );
    }

    public function cohorts(){
        return $this->belongsToMany( 'App\Cohort', 'users_2_cohorts' );
    }

    public function getAccessToken(){
        if( $this->authTokens()->count() > 0 ){
            $at = $this->authTokens()->first();
            $at->prolong();
            return $at;
        }

        return \App\AuthToken::createToken( $this->id );
    }

    public function notices(){
        return $this->hasMany( 'App\Notice' );
    }

    public function hasAccess(){
        $has_access = false;

        foreach( $this->freeAccess as $fa ) {
            if( \Carbon::now( 'UTC') < \Carbon::parse( $fa->valid_till, 'UTC' ) ){
                $has_access = true;
                break;
            }
        }
        if( !$has_access ){
            foreach( $this->receipts as $r ) {
                foreach( $r->inAppPurchases as $inapp ) {
                    if( $inapp->valid ){
                        $has_access = true;
                        break;
                    }
                }
            }
        }

        return $has_access;
    }

    public function cleanTokens(){
        foreach( $this->authTokens as $at )
            $at->cleanup();
    }

    public function init(){
        // исключительно для обратной совместимости с куском кода, в котором я могу забыть убрать этот init()
    }

    public static function getNamedList( $only_clients = false ){
        $to_ret = [];
        foreach( User::all() as $u ){
            if( $only_clients && $u->role != User::ROLE_USER )
                continue;
            $to_ret[ $u->id ] = '#' . $u->id . ' ' . $u->email;
        }

        return $to_ret;
    }

    static public function stringCurrencies(){
        $to_ret = '';
        $currencies = \DB::table('users')
                 ->select('store_country', \DB::raw('count(*) as total'))
                 ->groupBy('store_country')
                 ->orderBy( 'total', 'DESC' )
                 ->get();
        foreach( $currencies as $r ) {
            if( $r->store_country ){
                $to_ret .= $r->store_country . ' (' . $r->total . ') ';
            }
        }

        return $to_ret;
    }

    public function attributionSearchAdsInfo(){
        return $this->searchAdsInfo()->orderBy( 'iad-conversion-date', 'desc' )->first();
    }

    public function attributionChannel(){
        $sai = $this->searchAdsInfo()->orderBy( 'iad-conversion-date', 'desc' )->first();
        if( !$sai )
            return 'organic';
        else
            return '#' . $sai->getAttribute( 'iad-campaign-id' ) . ' ' . $sai->getAttribute( 'iad-campaign-name' );
    }

    public function cleanup(){
        $this->cohorts()->sync( [] );
        $this->searchAdsInfo()->sync( [] );
        $this->appInstalls()->sync( [] );

        foreach( $this->authTokens as $t )
            $t->cleanup();

        foreach( $this->appleOriginalTransactions as $aot ){
            $aot->cleanup();
        }

        foreach( $this->notices as $n )
            $n->cleanup();

        foreach( $this->receipts as $rp )
            $rp->cleanup();

        foreach( $this->freeAccess as $fa )
            $fa->cleanup();

        return $this->forceDelete();
    }
}
