<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserToken
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
        $user_token = $request->_user_token;
/*
        if( !$user_token ){
            $route_name = @$request->route()->getAction()[ 'as' ];
            if( in_array( $route_name, app('config')->get('app_logic')[ 'free_secret_routes' ] ) )
                return $next($request);
        }
*/
        if( !$user_token )
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], 'No user token provided' );

        $token = \App\AuthToken::where( 'token', $user_token )->first();
        if( !$token )
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], 'Invalid user token' );
        if( !$token->isValid() )
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['auth_expired'], 'User token expired' ); 

        return $next( $request );
    }
}
