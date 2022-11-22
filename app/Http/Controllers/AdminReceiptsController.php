<?php

namespace App\Http\Controllers;

use ReceiptValidator\iTunes\Validator as iTunesValidator;
use \App\Receipt;
use \App\InAppPurchase;
use \App\RenewalInfo;
use Illuminate\Http\Request;

class AdminReceiptsController extends Controller
{
    public function index(){
        $receipts_count = Receipt::count();
        $receipts = Receipt::orderBy( 'id', 'DESC' )->paginate( 20 );

        return \View::make( 'admin.resources.receipts.index', compact( 'receipts', 'receipts_count' ) );
    }

    public function show( $id ){
    	$receipt = Receipt::findOrFail( $id );

    	return \View::make( 'admin.resources.receipts.show', compact( 'receipt' ) );
    }

    public function stats(){
        $tester_ids = \App\User::where( 'tester', true )->pluck( 'id' )->all();
        $test_receipt_ids = \App\Receipt::whereIn( 'user_id', $tester_ids )->pluck( 'id' )->all();

        $active_subscriptions = InAppPurchase::where( 'valid', true )->whereNotIn( 'receipt_id', $test_receipt_ids )->count();
        $active_trial_subscriptions = InAppPurchase::where( 'valid', true )->whereNotIn( 'receipt_id', $test_receipt_ids )->where( 'is_trial_period', true )->count();
        $renewals = RenewalInfo::where( 'auto_renew_status', true )->whereNotIn( 'receipt_id', $test_receipt_ids )->where( 'is_in_billing_retry_period', false )->get();
        $billing_retry = RenewalInfo::where( 'auto_renew_status', true )->whereNotIn( 'receipt_id', $test_receipt_ids )->where( 'is_in_billing_retry_period', true )->count();
        $renewals_count = RenewalInfo::where( 'auto_renew_status', true )->whereNotIn( 'receipt_id', $test_receipt_ids )->where( 'is_in_billing_retry_period', false )->count();
        $stats = [];

        foreach( $renewals as $r ){
            $receipt = $r->receipt;
            $user = $receipt->user;
            $subscription = $r->subscription;
            $renewing_inapp = $receipt->inAppPurchases()->where( 'subscription_id', $subscription->id )->orderBy( 'expires_date', 'DESC' )->first();

            if( !$renewing_inapp )
                continue;

            $expires_date = explode( ' ', $renewing_inapp->expires_date )[ 0 ];

            $stats[ $expires_date ][] = [
                'receipt_id' => $receipt->id,
                'product_id' => $subscription->product_id,
                'name' => $subscription->name,
                'purchase_date' => $renewing_inapp->purchase_date,
                'expires_date' => $renewing_inapp->expires_date,
                'is_trial_period' => $renewing_inapp->is_trial_period,
                'user_id' => $user->id,
                'refunded' => $renewing_inapp->refunded,
            ];
        }

        ksort( $stats );

        return \View::make( 'admin.resources.receipts.stats', compact( 'stats', 'active_subscriptions', 'active_trial_subscriptions', 'renewals_count', 'billing_retry' ) );
    }

    public function validateReceipt( $id, $raw = false ){
        $receipt = Receipt::findOrFail( $id );

        if( \App::environment( [ 'local', 'staging' ] ) ){
            //\Log::info( 'Setting up SANDBOX validator' );
            $validator = new iTunesValidator( iTunesValidator::ENDPOINT_SANDBOX );
        }
        else{
            //\Log::info( 'Setting up PRODUCTION validator' );
            $validator = new iTunesValidator( iTunesValidator::ENDPOINT_PRODUCTION );
        }

        if( \Input::has( 'latest-receipt' ) )
            $receiptBase64Data = \Input::get( 'latest_receipt' );
        else
            $receiptBase64Data = $receipt->receipt;

        try {
          $sharedSecret = env( 'APPSTORE_SHARED_SECRET', null );
          $response = $validator->setSharedSecret( $sharedSecret )->setReceiptData( $receiptBase64Data )->validate();
        }
        catch( Exception $e ){
            $_notice[ 'type' ] = 'danger';
            $_notice[ 'message' ] = 'Receipt validation error: ' . $e->getMessage();

            return \Redirect::back()->with( compact( '_notice' ) );
        }

        if( $response->isValid() ){
            if( $raw )
                dd( $response->getRawData() );
            return \View::make( 'admin.resources.receipts.validation', compact( 'response', 'receipt' ) );
        }

        $_notice[ 'type' ] = 'danger';
        $_notice[ 'message' ] = 'Receipt not valid';

        return \Redirect::back()->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
    	$receipt = Receipt::findOrFail( $id );
    	$receipt->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Receipt deleted successfully';

    	return \Redirect::back()->with( compact( '_notice' ) );
    }

    public function billingRetries(){
        $tester_ids = \App\User::where( 'tester', true )->pluck( 'id' )->all();
        $test_receipt_ids = \App\Receipt::whereIn( 'user_id', $tester_ids )->pluck( 'id' )->all();

        $billing_retries_count = RenewalInfo::where( 'auto_renew_status', true )->whereNotIn( 'receipt_id', $test_receipt_ids )->where( 'is_in_billing_retry_period', true )->count();
        $billing_retries = RenewalInfo::where( 'auto_renew_status', true )->whereNotIn( 'receipt_id', $test_receipt_ids )->where( 'is_in_billing_retry_period', true )->orderBy( 'id', 'DESC' )->paginate( 10 );

        return \View::make( 'admin.resources.receipts.billing_retries', compact( 'billing_retries', 'billing_retries_count' ) );        
    }
}
