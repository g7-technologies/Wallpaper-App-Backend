<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPushNotificationsController extends Controller
{
    public function send( $notification_key ){
        return \View::make( 'admin.resources.push_notifications.send', compact( 'notification_key' ) );
    }

    public function sendDo( Request $request ){
        $this->validate( $request, [ 'body' => 'required', 'title' => 'required' ] );

        $data = [];
        if( \Input::has( 'img_url' ) )
            $data[ 'img_url' ] = \Input::get( 'img_url' );
        if( \Input::has( 'type' ) )
            $data[ 'type' ] = \Input::get( 'type' );

        $result = \App\Helper\sendPushNotification( \Input::get( 'notification_key' ), \Input::get( 'title' ), \Input::get( 'body' ), $data );

        if( $result ){
            $result_json = json_decode( $result );
            if( $result_json ){
                $_notice[ 'type' ] = 'danger';
                $_notice[ 'message' ] = 'Error sending push notification. Answer: ' . $result;
            }
            else{
                $_notice[ 'type' ] = 'success';
                $_notice[ 'message' ] = 'Push notification successfully sent. Answer: ' . $result;
            }
        }
        else{
            $_notice[ 'type' ] = 'danger';
            $_notice[ 'message' ] = 'No response from 3rd party service';
        }

        return \Redirect::back()->with( compact( '_notice' ) );
    }
}
