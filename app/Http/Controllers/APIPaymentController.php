<?php

namespace App\Http\Controllers;

use ReceiptValidator\iTunes\Validator as iTunesValidator;
use Illuminate\Http\Request;

use App\Receipt;
use App\InAppPurchase;
use App\Subscription;
use App\RenewalInfo;
use App\NonConsumable;
use App\PaygateOffer;

class APIPaymentController extends Controller
{
    public static function loginByAppleReceipt( $appleReceipt ){
        $original_transaction_ids = [];

        foreach( $appleReceipt->getPurchases() as $purchase ){
            if( in_array( $purchase[ 'original_transaction_id' ], $original_transaction_ids ) )
                continue;
            else
                $original_transaction_ids[] = $purchase[ 'original_transaction_id' ];
        }

        if( !sizeof( $original_transaction_ids ) )
            return null;

        $user = null;
        foreach( $original_transaction_ids as $oti ){
            $db_transaction = \App\AppleOriginalTransaction::where( 'original_transaction_id', $oti )->first();
            if( $db_transaction ){
                $user = $db_transaction->user;
                break;
            }
        }

        if( !$user ){
            // Новая подписка нового пользователя! Нужно создать пользователя и записать ему все транзакции в базу
            $user = \App\User::create( [
                'email' => time() . env('EMAIL_POSTFIX'),
                'name' => 'Some user',
                'gender' => 0,
                'password' => bcrypt( \App\Helper\randomString() ),
                'role' => \App\User::ROLE_USER,
                'tester' => false
            ] );
        }
        
        // нужно обновить для пользователя все его транзакции в базе
        foreach( $original_transaction_ids as $oti ){
            if( $user->appleOriginalTransactions()->where( 'original_transaction_id', $oti )->count() == 0 ){
                \App\AppleOriginalTransaction::create( [
                    'user_id' => $user->id,
                    'original_transaction_id' => $oti
                ] );
            }
        }

        return $user;
    }

    public static function parseReceipt( $response, $receipt_obj ){
        // check if we have more recent receipt
        if( $latestReceipt = $response->getLatestReceipt() ){
            if( $latestReceipt != $receipt_obj->receipt ){
                $receipt_obj->receipt = $latestReceipt;
                $receipt_obj->save();
            }
        }

        // Parse the in-app purchases information
        $purchase_count = 0;
        foreach( $response->getPurchases() as $purchase ){
            $purchase_count++;

            $product_id = $purchase->getProductId();
            $non_consumable = null;
            $subscription = null;

            $subscription = Subscription::where( 'product_id', $product_id )->first();
            if( !$subscription )
                $non_consumable = NonConsumable::where( 'product_id', $product_id )->first();

            if( !$subscription && !$non_consumable )
                continue;

            $already_have = $receipt_obj->inAppPurchases()->where( 'purchase_date', $purchase[ 'purchase_date' ] );

            if( $subscription )
                $already_have = $already_have->where( 'expires_date', $purchase[ 'expires_date' ] );

            $already_have = $already_have->count();
            if( $already_have )
                continue;

            $purchase_date = \Carbon::parse( $purchase[ 'purchase_date' ] );
            $expires_date = @\Carbon::parse( $purchase[ 'expires_date' ] );
            $now_date = \Carbon::now( 'UTC' );

            if( $subscription ){

                $ri = $receipt_obj->renewalInfo()->orderBy( 'updated_at', 'DESC' )->first();

                $in_app = InAppPurchase::create( [
                    'receipt_id' => $receipt_obj->id,
                    'subscription_id' => $subscription->id,
                    'transaction_id' => $purchase[ 'transaction_id' ],
                    'original_transaction_id' => $purchase[ 'original_transaction_id' ],
                    'purchase_date' => $purchase_date,
                    'expires_date' => $expires_date,
                    'is_trial_period' => $purchase[ 'is_trial_period' ] == 'true' ? true : false,
                    'valid' => $now_date < $expires_date ? true : false,
                    'analyzed' => false,
                    'auto_renew_status_on_purchase' => $ri ? $ri->auto_renew_status : true 
                ] );
            }
            elseif( $non_consumable ){
                $in_app = InAppPurchase::create( [
                    'receipt_id' => $receipt_obj->id,
                    'non_consumable_id' => $non_consumable->id,
                    'transaction_id' => $purchase[ 'transaction_id' ],
                    'original_transaction_id' => $purchase[ 'original_transaction_id' ],
                    'purchase_date' => $purchase_date,
                    //'expires_date' => $expires_date,
                    'is_trial_period' => false,
                    'valid' => $non_consumable->lifetime,
                    'analyzed' => false
                ] );
            }
        }

        if( !$purchase_count )
            $receipt_obj->cleanup();
        else{
            // Parse the renewal information
            $pendingRenewalInfo = $response->getPendingRenewalInfo();
            foreach( $pendingRenewalInfo as $info ){
                $orig_trans_id = $info[ 'original_transaction_id' ];

                $sub_obj = Subscription::where( 'product_id', $info[ 'product_id' ] )->first();
                if( !$sub_obj )
                    continue;

                $renew_obj = RenewalInfo::where( 'receipt_id', $receipt_obj->id )->where( 'original_transaction_id', $orig_trans_id )->first();

                $auto_renew_status =  0;
                if( isset( $info[ 'auto_renew_status' ] ) )
                    $auto_renew_status =  $info[ 'auto_renew_status' ] ? 1 : 0;

                $expiration_intent =  null;
                if( isset( $info[ 'expiration_intent' ] ) )
                    $expiration_intent =  $info[ 'expiration_intent' ];

                $is_in_billing_retry_period =  0;
                if( isset( $info[ 'is_in_billing_retry_period' ] ) )
                    $is_in_billing_retry_period =  $info[ 'is_in_billing_retry_period' ] ? 1 : 0;

                if( !$renew_obj ){
                    // create renew information record
                    $renew_obj = RenewalInfo::create( [
                        'receipt_id' => $receipt_obj->id,
                        'subscription_id' => $sub_obj->id,
                        'original_transaction_id' => $orig_trans_id,
                        'auto_renew_status' => $auto_renew_status,
                        'expiration_intent' => $expiration_intent,
                        'is_in_billing_retry_period' => $is_in_billing_retry_period,
                    ] );
                }
                else{
                    // update renew information record
                    // but before that check if the values had changed

                    if( $is_in_billing_retry_period != $renew_obj->is_in_billing_retry_period ){
                        if( $is_in_billing_retry_period ){
                            $user = $receipt_obj->user;
                            $user->save();
                        }
                        else if( !$is_in_billing_retry_period && $auto_renew_status ){
                        }
                        else if( !$is_in_billing_retry_period && !$auto_renew_status ){
                        }
                    }

                    if( $auto_renew_status != $renew_obj->auto_renew_status ){
                        if( $auto_renew_status ){
                        }
                        else{
                        }
                    }

                    if( $sub_obj->id != $renew_obj->subscription_id ){
                        $old_sub_obj = Subscription::find( $renew_obj->subscription_id );
                    }

                    $renew_obj->subscription_id = $sub_obj->id;
                    $renew_obj->auto_renew_status = $auto_renew_status;
                    $renew_obj->is_in_billing_retry_period = $is_in_billing_retry_period;
                    $renew_obj->expiration_intent = $expiration_intent;
                    $renew_obj->save();
                }
            }
        }
    }

    public function ping(){
        $validation = \Validator::make( \Input::all(), [ 'random_string' => 'required|string|max:255' ] );

        \Input::merge( [
            'last_ping_time' => \Carbon::now(),
            'processed' => false
        ] );

        if( $validation->fails() ){
            return \App\Helper\APIAnswer( app('config')->get('horo_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
        }

        $pv = \App\PaygateVisit::where( 'random_string', \Input::get( 'random_string' ) )->first();
        if( !$pv || !$pv->processed ){
            if( $pv ){
                $pv->fill( \Input::all() );
                $pv->save();
            }
            else
                $pv = \App\PaygateVisit::create( \Input::all() );
        }

        return \App\Helper\APIAnswer( app('config')->get('horo_logic')['api']['codes']['ok'], 'OK' );
    }

    public function paygate(){
        $validation = \Validator::make( \Input::all(), [ 'version' => 'required|integer' ] );
        if( $validation->fails() ){
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
        }

        $user = \App\Helper\APIGetUser();
        $version = \Input::get( 'version', null );
        $random_string = \Input::get( 'random_string', null );

        $offered_subscription = Subscription::where( 'product_id', 'LandscapeWallpaper_purchase.Weekly_w_Trial_offer' )->first();
		$special_subscription = Subscription::where( 'product_id', 'LandscapeWallpaper_purchase.LimitedMonthly' )->first();
        $lifetime = NonConsumable::where( 'product_id', 'LandscapeWallpaper_purchase.Lifetime' )->first();

        $already_used = false;
        $special_offer_used = $special_subscription ? false : true;
        if( $user ){
            foreach( $user->receipts as $r ){
                if( $r->inAppPurchases()->where( 'subscription_id', $offered_subscription->id )->count() )
                    $already_used = true;
                /*
                if( $r->inAppPurchases()->where( 'subscription_id', $special_subscription->id )->count() )
                    $special_offer_used = true;
                */
            }
        }
        if( $random_string ){
            $pv = \App\PaygateVisit::where( 'random_string', $random_string )->where( 'created_at', '<', \Carbon::now()->subMinutes( env( 'SPECIAL_OFFER_DELAY_MINS', 10 ) ) )->first();
            if( !$pv )
                $special_offer_used = true;
        }

        $offer_seconds = 60 * 60 - 1;
        if( !$special_offer_used ){
            if( $random_string ){
                $offer = PaygateOffer::where( 'random_string', $random_string )->first();
                if( !$offer ){
                    PaygateOffer::create( [ 'random_string' => $random_string ] );
                }
                else{
                    $offer_time = \Carbon::parse( $offer->created_at );
                    $now_time = \Carbon::now();
                    if( $offer_time->addHour() < $now_time ){
                        $special_offer_used = true;
                        //$offer->created_at = $now_time;
                        //$offer->save();
                    }
                    else
                        $offer_seconds = $now_time->diffInSeconds( $offer_time );
                }
            }
        }

        if( $version <= 8 ){
            $offered_subscription = Subscription::where( 'product_id', 'com.landscapewallpaper.monthly19.99' )->first();

            $paygate[ 'products' ] = [ $offered_subscription->product_id ];
            $paygate[ 'product_id' ] = $offered_subscription->product_id;
            $paygate[ 'header' ] = 'Unlock Everything';
            $paygate[ 'features' ] = [ 'Wallpapers in HD, optimized for your device', 'No ads. Weekly update deliveries. @price/mo. No commitment. Cancel anytime' ];
            $paygate[ 'pre_button' ] = '@price';
            $paygate[ 'button' ] = 'Continue';
        }
        else{
			
            $paygate = [
                'products' => [ $offered_subscription->product_id, $lifetime->product_id, $special_subscription->product_id ],
                'main' => [
                    'header' => 'Unlock Everything',
                    'text' => 'Wallpapers in HD, optimized for your device · Zero ads · Weekly update deliveries',
                    'options' => [
                        [   'product_id' => $lifetime->product_id,
                            'title' => 'Lifetime',
                            'caption' => '@price_div/week',
                            'div' => 53,
                            'subcaption' => 'in terms of 1 year',
                            'save' => 'SAVE 75%',
                            'bottom_line' => '@price'
                        ],
                        [   'product_id' => $offered_subscription->product_id,
                            'title' => $already_used ? '3 days free' : '3 days free',
                            //'title' => $already_used ? 'Monthly' : '3 days free',
                            'caption' => '@price/week',
                            'subcaption' => null,
                            'save' => null,
                            'bottom_line' => $already_used ? null : 'after 3 day trial'
                        ]
                    ],
                    'button' => 'CONTINUE',
                    'subbutton' => 'Secured with iTunes. Cancel anytime.',
                    'restore' => 'Restore',
                ]
            ];

            if( !$special_offer_used ){
                unset( $paygate[ 'main' ] );
                $paygate[ 'special_offer' ] = [
                    'title' => '-50%',
                    'subtitle' => 'Limited offer for new users',
                    'time_left' => date( 'i:s', $offer_seconds ),
                    'text' => 'Full access to the wallpapers library and weekly premium updates',
                    'product_id' => $special_subscription->product_id,
                    'special_offer_multiplicator' => 2,
                    'price_tag' => '@price per month',
                    'button' => 'Continue',
                    'subbutton' => 'Secured with iTunes. Cancel anytime.',
                    'restore' => 'Restore',
                ];
            }
        }

        return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', $paygate );
    }

    private function createFreeModeUser(){
        return \App\User::create( [
            'email' => time() . env('EMAIL_POSTFIX'),
            'name' => 'Free mode user',
            'gender' => 0,
            'password' => bcrypt( \App\Helper\randomString() ),
            'role' => \App\User::ROLE_USER,
            'tester' => false
        ] );
    }

    public function validateReceipt(){
        $validation = \Validator::make( \Input::all(), Receipt::$api_rules );
        if( $validation->fails() ){
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
        }

        $version = \Input::get( 'version', null );
        $user = \App\Helper\APIGetUser();

        if( \Input::get( 'receipt' ) == 'null' ){
            if( !$user && \App::environment( [ 'local', 'staging' ] ) )
                $user = $this->createFreeModeUser();

            if( $user )
                 return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'user_token' => $user->getAccessToken()->token, 'active_subscription' => $user->hasAccess() ? true : false, 'user_id' => $user->id ] );

            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], 'Null receipts are not accepted' );
        }

        if( \App::environment( [ 'local', 'staging' ] ) )
            $validator = new iTunesValidator( iTunesValidator::ENDPOINT_SANDBOX );
        else
            $validator = new iTunesValidator( iTunesValidator::ENDPOINT_PRODUCTION );

        $receiptBase64Data = \Input::get( 'receipt' );
        $validate_flag = false;
        for( $attemp = 0; $attemp < 3; $attemp++ ){
            try {
              $sharedSecret = env( 'APPSTORE_SHARED_SECRET', null );

              $response = $validator->setSharedSecret( $sharedSecret )->setReceiptData( $receiptBase64Data )->validate();
              $validate_flag = true;
              break;
            }
            catch( \Exception $e ){
                continue;
                //return \App\Helper\APIAnswer( app('config')->get('horo_logic')['api']['codes']['error'], $e->getMessage() );
            }
        }

        if( !$validate_flag )
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], $e->getMessage() );

        if( $response->isValid() ){
            if( !$user )
                $user = self::loginByAppleReceipt( $response );
            if( !$user ){
                // Проверим, включён ли бесплатный режим для версии пользователя
                $app_version = null;
                if( $version )
                    $app_version = \App\AppVersion::where( 'start_version', '<=', $version )->where( 'stop_version', '>=', $version )->first();
                if( !$app_version )
                    $app_version = \App\AppVersion::whereNull( 'start_version' )->whereNull( 'stop_version' )->first();
                if( !$app_version ){
                    \App\AppVersion::initDefaultVersion();
                    $app_version = \App\AppVersion::whereNull( 'start_version' )->whereNull( 'stop_version' )->first();
                }

                $app_settings = $app_version->appSettings;

                if( $app_settings && $app_settings->free_mode ){
                    // у нас бесплатный режим! Нужно завести пользователя
                    $user = $this->createFreeModeUser();

                     return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'user_token' => $user->getAccessToken()->token, 'active_subscription' => $user->hasAccess() ? true : false, 'user_id' => $user->id ] );
                }

                // Платный режим, говорим «пока»
                return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['access_denied'], 'Cannot create user record: the receipt is empty or invalid' );
            }

            $receipt_data = $response->getReceipt();
            $receipt_obj = $user->receipts()->where( 'bundle_id', @$receipt_data[ 'bundle_id' ] )->first();
            if( !$receipt_obj ){
                // save the receipt
                $receipt_obj = Receipt::create( [
                    'receipt' => $receiptBase64Data,
                    'user_id' => $user->id,
                    'receipt_type' => @$receipt_data[ 'receipt_type' ] ? $receipt_data[ 'receipt_type' ] : 'none',
                    'bundle_id' => @$receipt_data[ 'bundle_id' ] ? $receipt_data[ 'bundle_id' ] : 'none',
                    'app_version' => @$receipt_data[ 'application_version' ] ? $receipt_data[ 'application_version' ] : 'none',
                ] );
            }
            else{
                $receipt_obj->receipt = $receiptBase64Data;
                $receipt_obj->save();   
            }

            self::parseReceipt( $response, $receipt_obj );

            // вернуть аутентификационный токен пользователя
            $t = $user->getAccessToken();

            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'user_token' => $t->token, 'active_subscription' => $user->hasAccess() ? true : false, 'user_id' => $user->id ] );
        }

        return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], 'Receipt is not valid. Receipt result code ' . $response->getResultCode() );
    }
}
