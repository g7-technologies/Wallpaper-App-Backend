<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $table = 'app_versions';

	protected $guarded = [ 'id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'start_version', 'stop_version' ];

    public static $rules = [
            'start_version' => 'required|integer|lte:stop_version',
            'stop_version' => 'required|integer|gte:start_version'
        ];

    public static function initDefaultVersion(){
		if( \App\AppVersion::whereNull( 'start_version' )->whereNull( 'stop_version' )->count() == 0 )
			\App\AppVersion::create( [
            'start_version' => null,
            'stop_version' => null ] );
    }

    public function cleanup(){
    	if( !$this->isDefault() ){
            $app_settings = $this->appSettings;
            if( $app_settings )
                $app_settings->cleanup();
            
        	return $this->forceDelete();
        }
    }

    public function isDefault(){
        if( $this->start_version != null || $this->stop_version != null )
            return false;

        return true;
    }

    public function versionName(){
        if( $this->start_version != null || $this->stop_version != null )
            return 'from ' . $this->start_version . ' to ' . $this->stop_version;

        return 'default version';
    }

    public static function getList(){
        $to_ret = [];
        foreach( \App\AppVersion::all() as $av )
            $to_ret[ $av->id ] = $av->versionName();

        return $to_ret;
    }

    public static function ifIntersects( $start_version, $stop_version, $id ){
        foreach( [ $start_version, $stop_version ] as $v ){
            if( \App\AppVersion::where( 'start_version', '<', $v )->where( 'id', '<>', $id )->where( 'stop_version', '>', $v )->count() )
                return true;
        }

        return false;
    }

    public function appSettings(){
        return $this->hasOne( 'App\AppSettings' );
    }
}
