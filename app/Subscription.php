<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'name', 'product_id', 'billing_period', 'has_trial', 'trial_period', 'default_revenue' ];

    const TRIAL_3_DAYS = 1;
    const TRIAL_WEEK = 2;

    public static $trial_periods = [
    	self::TRIAL_3_DAYS => '3 days',
    	self::TRIAL_WEEK => 'Week'
    ];

    const BILLING_WEEKLY = 1;
    const BILLING_MONTHLY = 2;
    const BILLING_2_MONTH = 3;
    const BILLING_3_MONTH = 4;
    const BILLING_6_MONTH = 5;
    const BILLING_ANNUAL = 6;

    public static $billing_periods = [
    	self::BILLING_WEEKLY => 'Weekly',
    	self::BILLING_MONTHLY => 'Monthly',
    	self::BILLING_2_MONTH => '2 Month',
    	self::BILLING_3_MONTH => '3 Month',
    	self::BILLING_6_MONTH => 'Half annual',
    	self::BILLING_ANNUAL => 'Annual'
    ];

    public static $rules = [
    	'name' => 'required|min:2',
    	'product_id' => 'required|min:2|unique:subscriptions',
    	'billing_period' => 'required|integer',
    	'has_trial' => 'boolean',
    	'trial_period' => 'integer'
    ];

    public function inAppPurchases(){
        return $this->hasMany( 'App\InAppPurchase' );
    }

    public function subscriptionRevenues(){
        return $this->hasMany( 'App\SubscriptionRevenue' );
    }

    public function subscriptionRetentions(){
        return $this->hasMany( 'App\SubscriptionRetention' );
    }

    public function renewalInfo(){
        return $this->hasMany( 'App\RenewalInfo' );
    }

    public function statRecords(){
        return $this->hasMany( 'App\StatRecord' );
    }

    public function cleanup(){
        foreach( $this->statRecords as $sr )
            $sr->cleanup();

        foreach( $this->subscriptionRevenues as $sr )
            $sr->cleanup();

        foreach( $this->subscriptionRetentions as $srt )
            $srt->cleanup();

        foreach( $this->inAppPurchases as $iap )
            $iap->cleanup();

        foreach( $this->renewalInfo as $ri )
            $ri->cleanup();
        
        return $this->forceDelete();
    }
}
