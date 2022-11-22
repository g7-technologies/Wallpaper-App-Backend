<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhooksController extends Controller
{
    public function apple( Request $request ){
        $input = [];
        $json = json_decode( $request->getContent() );

        if( $json->password != env( 'APPSTORE_SHARED_SECRET' ) )
            return 'bye, spammer';

        if( $json->notification_type == 'CANCEL' ){
            //\App\Notice::alert( env( 'ADMIN_ID' ), $request->getContent() );
            // Нужно обработать уведомление о рефанде
            $web_order_id = $json->web_order_line_item_id;
            $original_transaction_id = null;
            $transaction_id = null;
            foreach( $json->unified_receipt->latest_receipt_info as $iap ){
                if( $iap->web_order_line_item_id == $web_order_id ){
                    $original_transaction_id = $iap->original_transaction_id;
                    $transaction_id = $iap->transaction_id;
                }
            }

            if( $original_transaction_id && $transaction_id ){
                // Ищем эту транзакцию
                if( $inapp = \App\InAppPurchase::where( 'original_transaction_id', $original_transaction_id )->where( 'transaction_id', $transaction_id )->first() ){
                    $mark_user = false;
                    if( !$inapp->refunded )
                        $mark_user = true;

                    $inapp->refunded = true;
                    $inapp->valid = false;
                    $inapp->save();

                    foreach( $inapp->statRecords as $sr ){
                        $sr->refunded = true;
                        $sr->save();
                    }

                    if( $mark_user ){
                        $user_id = $inapp->receipt->user_id;
                        \App\Notice::alert( $user_id, 'Refunded previous payment' );
                        $product = $inapp->subscription;
                        if( !$product )
                            $product = $inapp->nonConsumable;
                        \App\Helper\reportAmplitudeEvent( 'Payment refunded', $user_id,
                            [  'subscription_id' => $product->product_id ] );
                    }
                    return 'refund noted';
                }
            }
            return 'cannot find corresponding in-app purchase record';
        }

        return 'skipped';
    }
}
