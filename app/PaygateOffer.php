<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaygateOffer extends Model
{
    protected $table = 'paygate_offers';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'random_string' ];

    public static $api_rules = [
    	'random_string' => 'required|string|max:255'
    ];

    public function cleanup(){
        return $this->forceDelete();
    }
}
