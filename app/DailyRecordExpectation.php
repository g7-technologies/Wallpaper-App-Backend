<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyRecordExpectation extends Model
{
    protected $table = 'daily_record_expectations';

    protected $fillable = [
        'daily_record_id', 'period', 'value'
    ];

    public function dailyRecords(){
        return $this->belongsTo( 'App\DailyRecord' );
    }

    public function cleanup(){
        return $this->forceDelete();
    }
}
