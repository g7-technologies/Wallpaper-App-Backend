<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppVersion;
use App\AppSettings;

class AdminAppSettingsController extends Controller
{
    public function index(){
    	AppSettings::initDefaultSettings();
        $app_versions = AppVersion::orderBy( 'id', 'DESC' )->paginate( 20 );

        return \View::make( 'admin.resources.app_settings.index', compact( 'app_versions' ) );
    }

    public function editVersion( $version_id ){
    	if( !$version_id )
    		$app_version = new AppVersion;
    	else
    		$app_version = AppVersion::findOrFail( $version_id );

    	return \View::make( 'admin.resources.app_settings.edit_version', compact( 'app_version' ) );
    }

    public function editVersionDo( $version_id, Request $request ){
    	if( !$version_id )
    		$app_version = NULL;
    	else
    		$app_version = AppVersion::findOrFail( $version_id );

		$this->validate( $request, AppVersion::$rules );

		if( \App\AppVersion::ifIntersects( \Input::get( 'start_version' ), \Input::get( 'stop_version' ), $app_version ? $app_version->id : 0 ) ){
			$_notice[ 'type' ] = 'danger';
	        $_notice[ 'message' ] = 'App Version intersects with the existing one';

	    	return \Redirect::back()->with( compact( '_notice' ) )->withInput( $request->input() );
		}

		if( $app_version ){
			$app_version->fill( \Input::all() );
			$app_version->save();
		}
		else{
			$app_version = AppVersion::create( \Input::all() );
		}

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $version_id ? 'edited' : 'added' );

    	return \Redirect::to( route( 'admin.app_settings.index' ) )->with( compact( '_notice' ) );
    }

    public function deleteVersionDo( $version_id ){
    	$app_version = AppVersion::findOrFail( $version_id );
    	$app_version->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'App version removed successfully';

    	return \Redirect::back()->with( compact( '_notice' ) );
    }

    public function editSettings( $settings_id ){
    	if( !$settings_id )
    		$app_settings = new AppSettings;
    	else
    		$app_settings = AppSettings::findOrFail( $settings_id );

    	return \View::make( 'admin.resources.app_settings.edit_settings', compact( 'app_settings' ) );
    }

    public function editSettingsDo( $settings_id, Request $request ){
    	if( !$settings_id )
    		$app_settings = NULL;
    	else
    		$app_settings = AppSettings::findOrFail( $settings_id );

		$this->validate( $request, AppSettings::$rules );

		if( !$app_settings && AppSettings::where( 'app_version_id', \Input::get( 'app_version_id' ) )->count() ){
			$_notice[ 'type' ] = 'danger';
	        $_notice[ 'message' ] = 'App Settings for this version already exist';

	    	return \Redirect::back()->with( compact( '_notice' ) )->withInput( $request->input() );
		}

		if( $app_settings ){
			$app_settings->fill( \Input::all() );
			$app_settings->save();
		}
		else{
			$app_settings = AppSettings::create( \Input::all() );
		}

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $settings_id ? 'edited' : 'added' );

    	return \Redirect::to( route( 'admin.app_settings.index' ) )->with( compact( '_notice' ) );
    }

    public function deleteSettingsDo( $settings_id ){
    	$app_settings = AppSettings::findOrFail( $settings_id );

    	$av = $app_settings->appVersion;
    	if( $av && $av->isDefault() ){
			$_notice[ 'type' ] = 'danger';
	        $_notice[ 'message' ] = 'Cannot delete app settings for default app version';

	    	return \Redirect::back()->with( compact( '_notice' ) );
    	}

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully erased';

    	$app_settings->cleanup();
    	return \Redirect::back()->with( compact( '_notice' ) );    	
    }
}
