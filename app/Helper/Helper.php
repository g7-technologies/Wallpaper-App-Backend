<?php
	namespace App\Helper;

    function castUnderscores( $str ) {
        return str_replace( [ '/', ':' ], '_', $str);
    }

	function getSnippet( $str, $wordCount = 10 ) {
		return implode( '', array_slice( preg_split('/([\s,\.;\?\!]+)/', $str, $wordCount*2+1, PREG_SPLIT_DELIM_CAPTURE), 0, $wordCount*2-1 ) );
	}

    function saveCustomFile( $laravel_file, $upfile_basedir, $public = true, $square = false ){
        $my_path = $upfile_basedir . date( 'Y/m/' );
        if( $public ){
            if( !is_dir( public_path() . $my_path ) )
                mkdir( public_path() . $my_path, 0775, true );
        }
        else{
            if( !is_dir( storage_path() . $my_path ) )
                mkdir( storage_path() . $my_path, 0775, true );
        }

        $original_name = $laravel_file->getClientOriginalName();
        $filename = time() . $original_name;
        try {
            $disk_path = ( $public ? public_path() : storage_path() ) . $my_path . $filename;
            $ret = $laravel_file->move( ( $public ? public_path() : storage_path() ) . $my_path, $filename );
            if( $ret ){
                if( $square )
                    \App\Helper\squareImage( $disk_path );

                return [ 'path' => $my_path . $filename, 'original_name' => $original_name, 'size' => filesize( $disk_path ) ];
            }
            return NULL;
        } catch( \Exception $e ) {
            return NULL;
        }
        return NULL;
    }

    function isImage( $path ){
        $a = getimagesize($path);
        $image_type = $a[ 2 ];
        
        if( in_array(   $image_type,
                        [   IMAGETYPE_GIF, IMAGETYPE_JPEG,
                            IMAGETYPE_PNG, IMAGETYPE_BMP ] ) )
            return true;

        return false;
    }

	function randomString( $length = 8 ){
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen( $characters );
	    $randomString = '';
	    for( $i = 0; $i < $length; $i++ )
	        $randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
	    
	    return $randomString;
	}

	function randomNumber( $length = 8 ){
	    $characters = '0123456789';
	    $charactersLength = strlen( $characters );
	    $randomNumber = '';
	    for( $i = 0; $i < $length; $i++ )
	        $randomNumber .= $characters[ rand( 0, $charactersLength - 1 ) ];
	    
	    return $randomNumber;
	}

	function glueErrors( $validation ){
		$errors = $validation->errors();
		$out = null;

		if( sizeof( $errors ) == 1 ){
			$out = 'Data validation error: ';
			foreach( $errors->all() as $field => $error ){
				$out .= ' ' . $error;
			}
		}
		elseif( sizeof( $errors ) > 1 ){
			$counter = 1;
			$out = 'Data validation errors: ';
			foreach( $errors->all() as $field => $error ){
				$out .= ' ' . $counter . '. ' . $error;
				$counter++;
			}
		}

		return $out;
	}

	function br2nl( $string ){
	    $breaks = [ "<br />","<br>","<br/>" ];
	    return str_ireplace( $breaks, "\r\n", $string );
	}

	function APIAnswer( $code, $message, $params = [], $need_payment = false ){
		$params_size = sizeof( $params );
		if( $params == [] ){
			$params = json_encode( new \stdClass );
		}
		return \Response::json( [ '_code' => $code, '_msg' => $message, '_need_payment' => $need_payment, '_data' => $params_size ? $params : null ] );

		//return \Response::json( array_merge( [ '_code' => $code, '_msg' => $message, '_need_payment' => $need_payment ], [ '_data' => $params ] ) );
	}

	function APIGetUser(){
        $user_token = \Input::get( '_user_token', null );

		if( $user_token ){
			$at = \App\AuthToken::where( 'token', $user_token )->first();
			if( $at )
				return $at->user;
		}

		return null;
	}

	function saveFileToServer( $imageURL, $upfile_basedir, $filename = null, $public = true  ){
        $my_path = '/' . $upfile_basedir . date( 'Y/m/' );
        if( $public ){
            if( !is_dir( public_path() . $my_path ) )
                mkdir( public_path() . $my_path, 0777, true );
        }
        else{
            if( !is_dir( storage_path() . $my_path ) )
                mkdir( storage_path() . $my_path, 0777, true );
        }

		$content = file_get_contents( $imageURL );
		$url = $my_path . ( $filename ? $filename : ( time() . randomString() ) );
		$storage_path = ( $public ? public_path() : storage_path() ) . $url;

		//Store in the filesystem.
		$fp = fopen( $storage_path, "w" );
		fwrite( $fp, $content ); // todo: fallback for writing errors
		fclose( $fp );

		return $url;
	}

    function reportAmplitudeEvent( $event_name, $user_id, $event_properties = [], $user_properties = [] ){
    }

	function sendPushNotification( $notification_key, $title, $body, $data = [] ){
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = [	'to' => $notification_key,
		        	'notification' =>
		        		[	'title' => $title,
		    				'body' => $body ] ];
		if( isset( $data[ 'img_url' ] ) && $data[ 'img_url' ] ){
			$fields[ 'mutable_content' ] = true;
		}
		foreach( $data as $key => $value ){
			if( $value !== null )
				$fields[ 'data' ][ $key ] = $value;
		}

		//dd( $fields );

		$fields = json_encode( $fields );
		$headers = array (
		        'Authorization: key=' . env( "GOOGLE_FIREBASE_SERVER_KEY" ),
		        'Content-Type: application/json'
		);

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		$result = curl_exec ( $ch );

		return $result;
	}

	function sendToTelegram( $text ){
        $data = [
            'chat_id' => '@' . env( 'TELEGRAM_CHANNEL', '-' ),
            'text' => $text
        ];

        $response = file_get_contents( "https://api.telegram.org/bot" . env( 'TELEGRAM_BOT_KEY', '-' ) . "/sendMessage?" . http_build_query( $data ) );
	}

	function reportFBPurchase( /*$transaction_id, */$idfa, $ad_tracking = 1, $app_version = 1, $value = 3.49 ){
		if( !$app_version )
			$app_version = 1;

        system( 'curl \
		  -F "event=CUSTOM_APP_EVENTS" \
		  -F "advertiser_id=' . $idfa . '" \
		  -F "advertiser_tracking_enabled=' . ( $ad_tracking ? 1 : 0 ) . '" \
		  -F "application_tracking_enabled=1" \
		  -F "custom_events=[{\"_eventName\":\"fb_mobile_purchase\",\"fb_content_type\":\"subscription\", \"_valueToSum\":' . round( $value, 2 ) . ', \"fb_currency\":\"USD\", \"_appVersion\":\"' . $app_version . '\", \"_logTime\":' . time() . '}]" \
		  "https://graph.facebook.com/' . env( 'FACEBOOK_APP_ID' ) . '/activities" > /dev/null' );
	}

	function reportFBPurchaseCustom( $idfa, $user_id, $ad_tracking = 1, $app_version = 1, $value = 3.49 ){
		if( !$app_version )
			$app_version = 1;

        system( 'curl \
		  -F "event=CUSTOM_APP_EVENTS" \
		  -F "app_user_id=' . $user_id . '" \
		  -F "advertiser_id=' . $idfa . '" \
		  -F "advertiser_tracking_enabled=' . ( $ad_tracking ? 1 : 0 ) . '" \
		  -F "application_tracking_enabled=1" \
		  -F "custom_events=[{\"_eventName\":\"fb_mobile_purchase\",\"fb_content_type\":\"subscription\", \"_valueToSum\":' . round( $value, 2 ) . ', \"fb_currency\":\"USD\", \"_appVersion\":\"' . $app_version . '\", \"_logTime\":' . time() . '}]" \
		  "https://graph.facebook.com/' . env( 'FACEBOOK_APP_ID' ) . '/activities" > /dev/null 2> /dev/null' );
	}

	function reportFBTrialStart(/* $transaction_id, */$idfa, $ad_tracking = 1, $app_version = 1 ){
		if( !$app_version )
			$app_version = 1;

        system( 'curl \
		  -F "event=CUSTOM_APP_EVENTS" \
		  -F "advertiser_id=' . $idfa . '" \
		  -F "advertiser_tracking_enabled=' . ( $ad_tracking ? 1 : 0 ) . '" \
		  -F "application_tracking_enabled=1" \
		  -F "custom_events=[{\"_eventName\":\"StartTrial\",\"fb_content_type\":\"subscription\", \"_valueToSum\":0, \"fb_currency\":\"USD\", \"_appVersion\":\"' . $app_version . '\", \"_logTime\":' . time() . '}]" \
		  "https://graph.facebook.com/' . env( 'FACEBOOK_APP_ID' ) . '/activities" > /dev/null' );
	}

	function reportFBTrialStartCustom( $idfa, $user_id, $ad_tracking = 1, $app_version = 1 ){
		if( !$app_version )
			$app_version = 1;

        system( 'curl \
		  -F "event=CUSTOM_APP_EVENTS" \
		  -F "app_user_id=' . $user_id . '" \
		  -F "advertiser_id=' . $idfa . '" \
		  -F "advertiser_tracking_enabled=' . ( $ad_tracking ? 1 : 0 ) . '" \
		  -F "application_tracking_enabled=1" \
		  -F "custom_events=[{\"_eventName\":\"StartTrial\",\"fb_content_type\":\"subscription\", \"_valueToSum\":0, \"fb_currency\":\"USD\", \"_appVersion\":\"' . $app_version . '\", \"_logTime\":' . time() . '}]" \
		  "https://graph.facebook.com/' . env( 'FACEBOOK_APP_ID' ) . '/activities" > /dev/null 2> /dev/null' );
	}

    function reportBranchTrialStart( $idfa, $user_id, $ad_tracking, $subscription_product_id ){
        system( 'curl -vvv -d \'{"name": "START_TRIAL","customer_event_alias": "Trial Start","user_data": {"os": "iOS","environment": "FULL_APP","idfa": "' . $idfa . '","limit_ad_tracking": ' . ( $ad_tracking ? 'true' : 'false' ) . ',"developer_identity": "' . $user_id . '","country": "US","language": "en"},"custom_data": {"product_id": "' . $subscription_product_id . '"},"metadata": {},"branch_key": "' . env( 'BRANCH_KEY' ) . '"}\' https://api2.branch.io/v2/event/standard > /dev/null 2> /dev/null' );
    }

    function reportBranchPurchase( $idfa, $user_id, $locale, $transaction_id, $ad_tracking, $subscription_name, $subscription_product_id, $subscription_id, $value ){
        system( 'curl -vvv -d \'{"name": "PURCHASE","customer_event_alias": "Subscription Payment","user_data":{"os": "iOS","environment": "FULL_APP","idfa": "' . $idfa . '","limit_ad_tracking": ' . ( $ad_tracking ? 'true' : 'false' ) . ',"developer_identity": "' . $user_id . '","language": "' . $locale . '"},"event_data": {"transaction_id": "' . $transaction_id . '","currency": "USD","revenue": ' . $value . ',"affiliation": "Apple AppStore"},"content_items": [{"$content_schema": "COMMERCE_PRODUCT","$og_title": "' . $subscription_name . '","$canonical_identifier": "' . $subscription_product_id . '","$publicly_indexable": true,"$price": ' . $value . ',"$locally_indexable": true,"$quantity": 1,"$sku": "' . $subscription_id . '","$product_name": "' . $subscription_name . '","$product_brand": "' . env( 'APP_NAME' ) . '" }],"metadata": {},"branch_key": "' . env( 'BRANCH_KEY' ) . '"}\' https://api2.branch.io/v2/event/standard > /dev/null 2> /dev/null' );
    }

    function imagecreatefromfile( $filename ) {
        if (!file_exists($filename)) {
            return false;
        }
        switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
            case 'jpeg':
            case 'jpg':
            	try{
                	return imagecreatefromjpeg($filename);
            	}
            	catch( \Exception $e ){
            		return false;
            	}
            break;

            case 'png':
                return imagecreatefrompng($filename);
            break;

            case 'gif':
                return imagecreatefromgif($filename);
            break;

            default:
                return false;
            break;
        }
    }

    function makeThumb( $src, $savedir, $desired_width ){
        $my_path = $savedir . date( 'Y/m/' );
        if( !is_dir( public_path() . $my_path ) )
        	mkdir( public_path() . $my_path, 0775, true );

        /* read the source image */
        $source_image = imagecreatefromfile($src);
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = floor($height * ($desired_width / $width));

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        $dest_path = $my_path . time() . '_thumb_' . basename( $src );
        imagejpeg($virtual_image, public_path() . $dest_path );
        return $dest_path;
    }
