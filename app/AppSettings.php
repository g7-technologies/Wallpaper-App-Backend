<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    protected $table = 'app_settings';

	protected $guarded = [ 'id' ];
	protected $fillable = [ 'free_mode', 'app_version_id' ];

    public static $rules = [
    	'free_mode' => 'required|boolean',
        'app_version_id' => 'required|integer'
    ];

    public static function initDefaultSettings(){
    	\App\AppVersion::initDefaultVersion();

		$default_version = \App\AppVersion::whereNull( 'start_version' )->whereNull( 'stop_version' )->first();

		if( !$default_version->appSettings ){
			\App\AppSettings::create( [
                'free_mode' => false,
                'app_version_id' => $default_version->id ] );
		}
    }

    public function cleanup(){
    	$av = $this->appVersion;
    	if( $av && !$av->isDefault() )
        	return $this->forceDelete();
    }

    public function appVersion(){
        return $this->belongsTo( 'App\AppVersion' );
    }
}
