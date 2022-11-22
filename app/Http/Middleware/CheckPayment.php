<?php

namespace App\Http\Middleware;

use Closure;

class CheckPayment
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
        $route_name = @$request->route()->getAction()[ 'as' ];
        if( in_array( $route_name, [    'api.wallpapers.get',
                                        'api.wallpapers.check' ] ) ){
            if( strstr( $route_name, 'api.wallpapers.' ) ){
                $wallpaper = \App\Wallpaper::find( \Input::get( 'wallpaper_id', -1 ) );
                if( $wallpaper && $wallpaper->paid == false )
                    return $next($request);
            }

            $user_token = @$request->_user_token;
            if( $user_token ){
                $token = \App\AuthToken::where( 'token', $user_token )->first();
                $user = $token->user;

                if( $user->hasAccess() )
                    return $next($request);
            }

            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['access_denied'], 'Active subscription required', [], true );
        }

        return $next($request);
    }
}
