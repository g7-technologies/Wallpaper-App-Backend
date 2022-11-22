<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRetention extends Model
{
    protected $table = 'subscription_retentions';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'subscription_id', 'currency', 'retention', 'period' ];

    const PERIOD_1W = 1;
    const PERIOD_1M = 2;
    const PERIOD_2M = 3;

    public static $periods = [
        self::PERIOD_1W => '1 week',
        self::PERIOD_1M => '1 month',
        self::PERIOD_2M => '2 months',
    ];

    public static $colors = [
        self::PERIOD_1W => '#ff6666',
        self::PERIOD_1M => '#ffb3b3',
        self::PERIOD_2M => '#ffe6e6',
    ];

    public static $period_days = [
        self::PERIOD_1W => 6,
        self::PERIOD_1M => 32,
        self::PERIOD_2M => 62,
    ];

    public static $rules = [
        'subscription_id' => 'required|numeric',
    ];

    public function subscription(){
        return $this->belongsTo( 'App\Subscription' );
    }

    public function cleanup(){        
        return $this->forceDelete();
    }
}
