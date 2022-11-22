<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRevenue extends Model
{
     protected $table = 'subscription_revenues';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'subscription_id', 'revenue', 'currency', 'start_date', 'stop_date' ];

    public static $rules = [
    	'revenue' => 'required|numeric|between:0,999.99',
        'subscription_id' => 'required|numeric',
        'currency' => 'string|max:10',
        'start_date' => 'required|date',
        'stop_date' => 'date|nullable'
    ];

    public function subscription(){
        return $this->belongsTo( 'App\Subscription' );
    }

    public function cleanup(){        
        return $this->forceDelete();
    }
}
