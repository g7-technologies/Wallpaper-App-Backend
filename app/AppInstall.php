<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppInstall extends Model
{
   protected $table = 'app_installs';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'idfa', 'version', 'random_string' ];

    public static $rules = [
    	'idfa' => 'nullable|string|max:255',
    	'version' => 'required|numeric|min:0',
        'random_string' => 'required|string|max:255',
    ];

    public function searchAdsInfo(){
        return $this->belongsToMany( 'App\SearchAdsInfo', 'app_installs_2_search_ads_info' )->whereNotNull( 'iad-campaign-id' )->where( 'iad-campaign-id', '<>', '1234567890' );
    }

    public function users(){
        return $this->belongsToMany( 'App\User', 'users_2_app_installs' );
    }

    public function anonymous(){
        return $this->belongsToMany( 'App\Anonymous', 'anonymous_2_app_installs' );
    }

    public function attributionChannel(){
        $sai = $this->searchAdsInfo()->orderBy( 'iad-conversion-date', 'desc' )->first();
        if( !$sai )
            return 'organic';
        else
            return '#' . $sai->getAttribute( 'iad-campaign-id' ) . ' ' . $sai->getAttribute( 'iad-campaign-name' );
    }

    public function attributionSearchAdsInfo(){
        return $this->searchAdsInfo()->orderBy( 'iad-conversion-date', 'desc' )->first();
    }

    public function info(){
        $info[ 'is_reinstall' ] = false;
        $info[ 'valid_from' ] = $this->created_at;
        $info[ 'valid_till' ] = null;

        // является ли переустановкой?
        // Сначала смотрим по IDFA, если он не нулевой
        if( $this->idfa && $this->idfa != '00000000-0000-0000-0000-000000000000' ){
            $check = self::where( 'idfa', $this->idfa )->where( 'id', '<>', $this->id )->where( 'created_at', '<', $this->created_at )->count();
            if( $check > 0 )
                $info[ 'is_reinstall' ] = true;
        }
        // если всё ещё не определили, то проверяем, а вдруг у пользователей, которые сгенерировались в рамках этой установки были установки ранее?
        if( $info[ 'is_reinstall' ] == false ){
            foreach( $this->users as $u ){
                if( $info[ 'is_reinstall' ] )
                    break;

                if( $u->created_at < $this->created_at ){
                    $info[ 'is_reinstall' ] = true;
                    break;
                }

                foreach( $u->appInstalls()->where( 'app_install_id', '<>', $this->id )->get() as $ai ){
                    if( $ai->created_at < $this->created_at ){
                        $info[ 'is_reinstall' ] = true;
                        break;
                    }
                }
            }
        }

        // Надо понять диапазон валидности установки
        if( $this->idfa && $this->idfa != '00000000-0000-0000-0000-000000000000' ){
            $next_install = self::where( 'idfa', $this->idfa )->where( 'id', '<>', $this->id )->where( 'created_at', '>', $this->created_at )->orderBy( 'created_at', 'ASC' )->first();
            if( $next_install )
                $info[ 'valid_till' ] = $next_install->created_at;
        }

        $closest_date = null;
        foreach( $this->users as $u ){
            foreach( $u->appInstalls()->where( 'app_install_id', '<>', $this->id )->get() as $ai ){
                if( \Carbon::parse( $ai->created_at ) > \Carbon::parse( $this->created_at ) ){
                    if( !$closest_date )
                        $closest_date = $ai->created_at;
                    elseif( \Carbon::parse( $ai->created_at ) < \Carbon::parse( $closest_date ) )
                        $closest_date = $ai->created_at;
                }
            }
        }

        if( !$info[ 'valid_till' ] )
            $info[ 'valid_till' ] = $closest_date;
        elseif( \Carbon::parse( $info[ 'valid_till' ] ) > \Carbon::parse( $closest_date ) )
            $info[ 'valid_till' ] = $closest_date;

        return $info;
    }

    public function cleanup(){
    	$this->searchAdsInfo()->sync( [] );
        $this->users()->sync( [] );
        //$this->anonymous()->sync( [] );
        
        return $this->forceDelete();
    }
}
