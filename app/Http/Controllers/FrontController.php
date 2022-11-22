<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    function index(){
    	//dd( array_unique( array_merge( [ 1, 2, 3 ], [ 3, 4, 5 ] ) ) );
    	//return \View::make( 'front.index' );
    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    }
}
