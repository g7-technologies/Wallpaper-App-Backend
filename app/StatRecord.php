<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatRecord extends Model
{
    protected $table = 'stat_records';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'value', 'is_trial', 'is_reinstall', 'install_timestamp', 'app_install_id', 'in_app_purchase_id', 'subscription_id', 'non_consumable_id', 'search_ads_info_id', 'sequence_number', 'currency', 'value_potential', 'refunded' ];

    public static $rules = [
    ];

    public function subscription(){
        return $this->belongsTo( 'App\Subscription' );
    }

    public function nonConsumable(){
        return $this->belongsTo( 'App\NonConsumable' );
    }

    public function searchAdsInfo(){
        return $this->belongsTo( 'App\SearchAdsInfo' );
    }

    public function inAppPurchase(){
        return $this->belongsTo( 'App\InAppPurchase' );
    }

    public function appInstall(){
        return $this->belongsTo( 'App\AppInstall' );
    }

    public function cleanup(){
        return $this->forceDelete();
    }
}
