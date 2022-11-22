<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InAppPurchase extends Model
{
    protected $table = 'in_app_purchases';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'receipt_id', 'subscription_id', 'non_consumable_id', 'transaction_id', 'original_transaction_id', 'purchase_date', 'expires_date', 'is_trial_period', 'valid', 'refunded', 'analyzed', 'auto_renew_status_on_purchase' ];

    public static $rules = [
    ];

    public static $vat = [
        'RUB' => 0,
        'EUR' => 0.168,
        'USD' => 0,
        'BRL' => 0,
        'GBP' => 0.168,
        'UAH' => 0,
        'AUD' => 0.087,
        'KRW' => 0.088,
        'CAD' => 0,
        'SAR' => 0.048,
        'AED' => 0.048,
    ];

    public static function currenciesArray(){
        $out = [];
        foreach( self::$vat as $cur => $tax ){
            $out[ $cur ] = $cur;
        }
        return $out;
    }

    public static function isTestPurchase( $purchase_date, $expires_date ){
        $days_diff = \Carbon::parse( $purchase_date )->diffInDays( $expires_date );
        if( $days_diff < 2 )
            return true;
        return false;
    }

    public function isTest(){
        if( $this->subscription_id )
            return self::isTestPurchase( $this->purchase_date, $this->expires_date );

        $receipt = $this->receipt;
        return strstr( $receipt->receipt_type, 'Sandbox' ) ? true : false;
    }

    public function getRevenue( $user_currency = null ){
        if( !$user_currency )
            $user_currency = $this->receipt->user->store_country;
        
        if( $this->non_consumable_id ){
            $non_consumable = $this->nonConsumable;
            $revenue = $non_consumable->default_revenue;
            
            $minus = isset( self::$vat[ $user_currency ] ) ? $revenue * self::$vat[ $user_currency ] : 0;

            return ( $revenue - $minus );
        }

        $sub = $this->subscription;
        $sub_revenue = $sub->default_revenue;
        if( $user_currency ){
            $rev_info = $sub->subscriptionRevenues()->where( 'start_date', '>=', explode( ' ', $this->purchase_date )[ 0 ] )->where( 'stop_date', '<=', explode( ' ', $this->purchase_date )[ 0 ] )->where( 'currency', $user_currency )->first();
            if( $rev_info )
                $sub_revenue = $rev_info->revenue;
        }

        $minus = isset( self::$vat[ $user_currency ] ) ? $sub_revenue * self::$vat[ $user_currency ] : 0;

        return ( $sub_revenue - $minus );
    }

    public function subscription(){
        return $this->belongsTo( 'App\Subscription' );
    }

    public function nonConsumable(){
        return $this->belongsTo( 'App\NonConsumable' );
    }

    public function receipt(){
        return $this->belongsTo( 'App\Receipt' );
    }

    public function fbReportLogs(){
        return $this->hasMany( 'App\FBReportLog' );
    }

    public function statRecords(){
        return $this->hasMany( 'App\StatRecord' );
    }

    public function cleanup(){
        foreach( $this->statRecords as $sr )
            $sr->cleanup();

        foreach( $this->fbReportLogs as $fbrl )
            $fbrl->cleanup();
        
        return $this->forceDelete();
    }
}
