<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyRecord extends Model
{
    protected $table = 'daily_records';

    protected $fillable = [
        'day', 'installs', 'value', 'channel', 'campaign', 'adgroup', 'keyword', 'currency', 'trials'
    ];

    public function expectations(){
        return $this->hasMany( 'App\DailyRecordExpectation' );
    }

    public function cleanup(){
    	foreach( $this->expectations as $e )
    		$e->cleanup();
    	
        return $this->forceDelete();
    }
}
