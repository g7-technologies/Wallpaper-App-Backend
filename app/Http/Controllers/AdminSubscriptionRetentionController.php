<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Subscription;
use \App\SubscriptionRetention;

class AdminSubscriptionRetentionController extends Controller
{
    public function index(){
    	/*
    	$currencies = \DB::table( 'subscription_retentions' )
    					->select( \DB::raw( 'DISTINCT currency, COUNT(*) AS count_currency' ) )
    					->groupBy( 'currency' )
    					->orderBy( 'count_currency', 'DESC' )->get();
    	*/
    	$retentions = [];
    	$subscriptions = [];
    	foreach( Subscription::all() as $s ){
    		$subscriptions[ $s->id ] = $s;
    	}

    	foreach( SubscriptionRetention::all() as $srt ){
    		$retentions[ $srt->currency ][ $srt->period ][ $srt->subscription_id ] = $srt->retention;
    	}

    	return \View::make( 'admin.resources.retentions.index', compact( 'retentions', 'subscriptions' ) );
    }
}
