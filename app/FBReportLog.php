<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FBReportLog extends Model
{
    protected $table = 'fb_report_logs';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'in_app_purchase_id' ];

    public function inAppPurchase(){
        return $this->belongsTo( 'App\InAppPurchase' );
    }

    public function cleanup(){
        return $this->forceDelete();
    }
}
