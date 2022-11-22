<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NonConsumable extends Model
{
    protected $table = 'non_consumables';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'name', 'product_id', 'default_revenue', 'lifetime' ];

    public static $rules = [
    	'name' => 'required|min:2',
    	'product_id' => 'required|min:2|unique:subscriptions',
    	'lifetime' => 'boolean',
    ];

    public function inAppPurchases(){
        return $this->hasMany( 'App\InAppPurchase' );
    }

    public function statRecords(){
        return $this->hasMany( 'App\StatRecord' );
    }

    public function cleanup(){
        foreach( $this->statRecords as $sr )
            $sr->cleanup();

        foreach( $this->inAppPurchases as $iap )
            $iap->cleanup();

        return $this->forceDelete();
    }
}
