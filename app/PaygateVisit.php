<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaygateVisit extends Model
{
    protected $table = 'paygate_visits';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'random_string', 'last_ping_time', 'processed' ];

    public static $api_rules = [
    	'random_string' => 'required|string|max:255'
    ];

    public function cleanup(){
        return $this->forceDelete();
    }
}
