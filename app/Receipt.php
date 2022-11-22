<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'user_id', 'receipt', 'receipt_type', 'bundle_id', 'app_version' ];

    public static $rules = [
    	'receipt' => 'required',
    	'user_id' => 'required|integer',
        'receipt_type' => 'required',
        'bundle_id' => 'required',
        'app_version' => 'required'
    ];

    public static $api_rules = [
    	'receipt' => 'required',
        'version' => 'integer'
    ];

    public function user(){
        return $this->belongsTo( 'App\User' );
    }

    public function inAppPurchases(){
        return $this->hasMany( 'App\InAppPurchase' );
    }

    public function renewalInfo(){
        return $this->hasMany( 'App\RenewalInfo' );
    }

    public function cleanup(){
        foreach( $this->inAppPurchases as $iap )
            $iap->cleanup();

        foreach( $this->renewalInfo as $ri )
            $ri->cleanup();

        return $this->forceDelete();
    }
}
