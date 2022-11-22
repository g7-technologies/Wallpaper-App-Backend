<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Anonymous;
use \App\Notice;

class APIAnonymousController extends Controller
{
    public function create(){
	    $validation = \Validator::make( \Input::all(), Anonymous::$api_edit_rules );
	    if( $validation->fails() ){
	    		Notice::alert( env( 'ADMIN_ID' ), 'trying to save anonymous user information. ' . \App\Helper\glueErrors( $validation ), Notice::LEVEL_ERROR, Notice::TYPE_USER );
	    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
	    }

	    $ar = null;
	    if( $ar = Anonymous::where( 'notification_key', \Input::get( 'notification_key' ) )->first() ){
	    	$ar->fill( \Input::all() );
	    	$ar->save();
	    	
	    	Notice::alert( env( 'ADMIN_ID' ), 'Updated anonymous record', Notice::LEVEL_INFO, Notice::TYPE_USER );
	    }
	    else{
		    $ar = Anonymous::create( \Input::all() );
	        \App\Helper\reportAmplitudeEvent( 'Anonymous Created', env( 'ADMIN_ID' ) );

			Notice::alert( env( 'ADMIN_ID' ), 'Created anonymous record', Notice::LEVEL_INFO, Notice::TYPE_USER );
	    }
/*
	    if( \Input::has( 'random_string' ) ){
	    	$ai = \App\AppInstall::where( 'random_string', $ar->random_string )->first();
	    	if( $ai &&
	    		$ar->appInstalls()->where( 'app_install_id', $ai->id )->count() == 0 )
	    		$ar->appInstalls()->attach( $ai->id );
	    }
*/
	    return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    }
}
