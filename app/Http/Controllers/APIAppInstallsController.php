<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppInstall;
use App\SearchAdsInfo;

class APIAppInstallsController extends Controller
{
    public function register(){
	    $validation = \Validator::make( \Input::all(), AppInstall::$rules );
	    if( $validation->fails() )
	    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );

	    $ai = AppInstall::create( \Input::all() );

	    // узнать, передали ли нам атрибуцию Search Ads
    	$found_iad = false;
    	foreach( \Input::all() as $key => $value ){
    		if( substr( $key, 0, 4 ) === "iad-" ){
    			$found_iad = true;
    			break;
    		}
    	}
    	if( !$found_iad )
    		return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );

        if( \Input::has( 'iad-attribution' ) ){
            if( in_array( \Input::get( 'iad-attribution' ), [ 'false', false, 0 ] ) )
                //return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
                \Input::merge( [ 'iad-attribution' => false ] );
            else{
                \Input::merge( [ 'iad-attribution' => true ] );
            }
        }

        foreach( SearchAdsInfo::$timefields as $key ) {
            if( \Input::has( $key ) )
                \Input::merge( [ $key => str_replace( [ 'T', 'Z' ], [ ' ', '' ], \Input::get( $key ) ) ] );
        }

	    $validation = \Validator::make(
	    					\Input::all(),
	    					\App\SearchAdsInfo::$rules );
	    if( $validation->fails() )
	    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );

        $sai_check = \App\SearchAdsInfo::where( 'id', '>', 0 );
        $fillable = [];
        foreach( \App\SearchAdsInfo::$rules as $field => $rule )
            $fillable[]= $field;

        foreach( \Input::all() as $key => $value ){
            if( in_array( $key, $fillable ) )
                $sai_check = $sai_check->where( $key, $value );
        }
        $sai_check = $sai_check->first();

        if( !$sai_check ){
            $sai = \App\SearchAdsInfo::create( \Input::all() );
            $ai->searchAdsInfo()->attach( $sai->id );
        }
        else
            $ai->searchAdsInfo()->attach( $sai_check->id );

    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    }
}
