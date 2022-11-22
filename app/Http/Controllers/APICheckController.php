<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APICheckController extends Controller
{
    public function APIKey(){
    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    }

    public function userToken(){
    	$user = \App\Helper\APIGetUser();

    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'user_token' => $user->getAccessToken()->token, 'active_subscription' => $user->hasAccess() ? true : false, 'user_id' => $user->id ] );
    }
}