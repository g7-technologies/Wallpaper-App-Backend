<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Anonymous;
use \App\User;
use \App\Notice;
use \App\AuthToken;
use \App\SearchAdsInfo;

class APIUserController extends Controller
{
    public function show(){
    	$user = \App\Helper\APIGetUser();

	    return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [
	    	'id' => $user->id,
	    	'timezone' => $user->timezone,
	    	'locale' => $user->locale,
	    	'version' => $user->version,
	    ] );
    }

    public function setParameter(){
    	$user = \App\Helper\APIGetUser();

    	$keys = [];
    	foreach( \App\User::$api_edit_rules as $k => $v )
    		$keys[] = $k;
    	\Input::replace( \Input::only( $keys ) );
	    $validation = \Validator::make( \Input::all(), \App\User::$api_edit_rules );

	    if( $validation->fails() ){
	    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
	    }

	    $user->fill( \Input::all() );
	    $user->save();
		$user->init();

	    if( \Input::has( 'random_string' ) ){
	    	$ai = \App\AppInstall::where( 'random_string', $user->random_string )->first();
	    	if( $ai &&
	    		$user->appInstalls()->where( 'app_install_id', $ai->id )->count() == 0 )
	    		$user->appInstalls()->attach( $ai->id );
	    }

        if( \Input::has( 'idfa' ) && $user->idfa && $user->idfa != '00000000-0000-0000-0000-000000000000' ){
            $ais = \App\AppInstall::where( 'idfa', $user->idfa )->get();
            foreach( $ais as $ai ){
                if( $user->appInstalls()->where( 'app_install_id', $ai->id )->count() == 0 )
                    $user->appInstalls()->attach( $ai->id );
            }
        }

        if( \Input::has( 'notification_key' ) ){
            $ar = \App\Anonymous::where( 'notification_key', \Input::get( 'notification_key' ) )->first();
            if( $ar )
                $ar->cleanup();
        }

	    return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    }

    public function addSearchAdsInfo(){
    	$user = \App\Helper\APIGetUser();

    	$found_iad = false;
    	foreach( \Input::all() as $key => $value ){
    		if( substr( $key, 0, 4 ) === "iad-" ){
    			$found_iad = true;
    			break;
    		}
    	}
    	if( !$found_iad )
    		return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );

    	//\Input::merge( [ 'user_id' => $user->id ] );

    	if( $user->SearchAdsInfo()->where( 'iad-campaign-id', \Input::get( 'iad-campaign-id', null ) )->where( 'iad-lineitem-id', \Input::get( 'iad-lineitem-id', null ) )->where( 'iad-adgroup-id', \Input::get( 'iad-adgroup-id', null ) )->where( 'iad-keyword-id', \Input::get( 'iad-keyword-id', null ) )->where( 'iad-conversion-date', \Input::get( 'iad-conversion-date', null ) )->count() ){
    		// не дублируем информацию про установки
    		return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    	}

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

	    $validation = \Validator::make( \Input::all(), SearchAdsInfo::$rules );
	    if( $validation->fails() ){
	    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
	    }

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
            $sai = SearchAdsInfo::create( \Input::all() );
            $user->searchAdsInfo()->attach( $sai->id );
        }
        else
            $user->searchAdsInfo()->attach( $sai_check->id );

    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    }
}
