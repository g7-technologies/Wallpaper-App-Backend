<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function logout(){
        \Session::flush();
        \Auth::logout();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Logged out successfully';

        return \Redirect::route( 'front.index' )->with( compact( '_notice' ) );
    }

    public function login(){
        if( $u = \Auth::user() ){
	        $_notice[ 'type' ] = 'warning';
	        $_notice[ 'message' ] = 'Already logged in';


            if( $u->role == \App\User::ROLE_ADMIN )
	           return \Redirect::route( 'admin.index' )->with( compact( '_notice' ) );
        }

/*
        $u = \App\User::where( 'email', 'legal@wallpapers.wiki' )->first();
        $u->password = bcrypt( 'iwantsomeWIKIWALLPAPERS1!' );
        $u->save();
*/

        return \View::make( 'front.auth.login' );
    }

    public function loginDo(){
/*
        $u = \App\User::where( 'email', 'legal@yourhoro.com' )->first();
        $u->password = bcrypt( 'horo24CS03' );
        $u->save();
*/
        if( \Auth::attempt(  [   'email' => \Input::get( 'email' ),
                                'password' => \Input::get( 'password' ) ], true ) ){
            $_notice[ 'type' ] = 'success';
            $_notice[ 'message' ] = 'Logged in successfully';

            if( in_array( \Auth::user()->role, [ \App\User::ROLE_ADMIN ] ) )
                return \Redirect::route( 'admin.index' );
            else
                return \Redirect::route( 'front.index' );
        }

        $_notice[ 'type' ] = 'danger';
        $_notice[ 'message' ] = 'Error loggin in';

        return \Redirect::back()->with( compact( '_notice' ) )->withInput( \Input::all() );
    }
}
