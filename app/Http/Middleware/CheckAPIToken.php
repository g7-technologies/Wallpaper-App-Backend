<?php

namespace App\Http\Middleware;

use Closure;

class CheckAPIToken
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
/*
        \Log::info( $request->path() );
        \Log::info( \Input::all() );
*/
         if( isset( $request->_api_key ) &&
            in_array( $request->_api_key, explode( ',', env( 'API_KEYS', null ) ) ) )
            return $next($request);


        return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['internal_error'], 'API key mismatch' );
    }
}
