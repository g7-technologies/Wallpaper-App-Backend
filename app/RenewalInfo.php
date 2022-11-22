<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenewalInfo extends Model
{
    protected $table = 'renewal_info';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'receipt_id', 'subscription_id', 'original_transaction_id', 'auto_renew_status', 'expiration_intent', 'is_in_billing_retry_period' ];

    public static $rules = [
    ];

    public static $expirationIntents = [
        1 => 'canceled by customer',
        2 => 'billing error (unable to charge)',
        3 => 'did not agree to price increase',
        4 => 'product was unavailable',
        5 => 'unknown'
    ];

    public static function getExpirationIntentInfo( $code ){
        if( @self::$expirationIntents[ $code ] )
            return self::$expirationIntents[ $code ];
        else
            return 'unknown';
    }

    public function receipt(){
        return $this->belongsTo( 'App\Receipt' );
    }

    public function subscription(){
        return $this->belongsTo( 'App\Subscription' );
    }

    public function user(){
        return $this->receipt->user;
    }

    public function cleanup(){
        return $this->forceDelete();
    }
}
