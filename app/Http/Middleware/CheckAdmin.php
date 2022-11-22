<?php

namespace App\Http\Middleware;

use Closure;
use \App\User;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( \Auth::check() ){
            $user = \Auth::user();
            if( in_array( $user->role, [ User::ROLE_ADMIN ] ) )
                return $next($request);
        }

        $_notice[ 'type' ] = 'danger';
        $_notice[ 'message' ] = 'This part is for administrative use only';

        if( $request->ajax() )
            return \Response::json( [ 'success' => false, 'msg' => $_notice[ 'msg' ] ] );
        
        return \Redirect::route( 'front.auth.login' )->with( compact( '_notice' ) );
    }
}
