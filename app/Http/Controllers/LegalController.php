<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    function policy(){
    	return \View::make( 'front.policy' );
    }

    function terms(){
    	return \View::make( 'front.terms' );
    }

    function contact(){
    	return \View::make( 'front.contact' );
    }
}
