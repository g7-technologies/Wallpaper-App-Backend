<?php

namespace App\Http\Controllers;

use App\User;
use App\Subscription;
use App\Notice;
use App\SubscriptionRevenue;

use Illuminate\Http\Request;

class AdminSubscriptionsController extends Controller
{
    public function index(){
        $subscriptions = Subscription::orderBy( 'id', 'DESC' )->paginate( 20 );

        return \View::make( 'admin.resources.subscriptions.index', compact( 'subscriptions' ) );
    }

    public function show( $id ){
    	$subscription = Subscription::findOrFail( $id );

    	return \View::make( 'admin.resources.subscriptions.show', compact( 'subscription' ) );
    }

    public function edit( $id ){
    	if( !$id )
    		$subscription = new Subscription;
    	else
    		$subscription = Subscription::findOrFail( $id );

    	return \View::make( 'admin.resources.subscriptions.edit', compact( 'subscription' ) );
    }

    public function editDo( $id, Request $request ){
    	if( !$id )
    		$subscription = NULL;
    	else
    		$subscription = Subscription::findOrFail( $id );

    	$rules = Subscription::$rules;
    	if( $subscription  ){	
    		if( $subscription->product_id == \Input::get( 'product_id', NULL ) )
    			unset( $rules[ 'product_id' ] );
    		if( \Input::get( 'has_trial' ) != true ){
    			\Input::replace( \Input::except( 'trial_period' ) );
    			unset( $rules[ 'trial_period' ] );
    		}
    	}

		$this->validate( $request, $rules );

		if( $subscription ){
			$subscription->fill( \Input::all() );
			$subscription->save();
		}
		else{
			$subscription = Subscription::create( \Input::all() );
		}

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

    	return \Redirect::to( route( 'admin.subscriptions.show', $subscription->id ) )->with( compact( '_notice' ) );
    }

    public function editRevenue( $subscription_id, $id ){
        if( !$id )
            $subscription_revenue = new SubscriptionRevenue;
        else
            $subscription_revenue = SubscriptionRevenue::findOrFail( $id );

        $subscription = Subscription::findOrFail( $subscription_id );

        return \View::make( 'admin.resources.subscriptions.edit_revenue', compact( 'subscription', 'subscription_revenue' ) );
    }

    public function editRevenueDo( $subscription_id, $id, Request $request ){
        if( !$id )
            $subscription_revenue = NULL;
        else
            $subscription_revenue = SubscriptionRevenue::findOrFail( $id );

        \Input::merge( [ 'subscription_id' => $subscription_id ] );
        $this->validate( $request, SubscriptionRevenue::$rules );

        // Проверить пересечение с уже существующими записями о доходе в этой стране
        foreach( [ \Input::get( 'start_date' ), \Input::get( 'stop_date', null ) ] as $d ){
            if( $d == null )
                $d = '2222-01-01';

            if( SubscriptionRevenue::where( 'subscription_id', $subscription_id )->where( 'currency', \Input::get( 'currency' ) )->where( 'start_date', '<', $d )->where( 'id', '<>', $id )->where( function( $query ) use ( $d ) { $query->where( 'stop_date', '>', $d )->orWhereNull( 'stop_date' ); } )->count() ){
                $_notice[ 'type' ] = 'danger';
                $_notice[ 'message' ] = 'These values intersect with already existing record';
                return \Redirect::back()->withInput()->with( compact( '_notice' ) );
            }
        }

        $start_date = \Input::get( 'start_date' );
        $stop_date = \Input::get( 'stop_date' );
        if( !$stop_date )
            $stop_date = '2222-01-01';

        if( SubscriptionRevenue::where( 'subscription_id', $subscription_id )->where( 'currency', \Input::get( 'currency' ) )->where( 'start_date', '>', $start_date )->where( 'id', '<>', $id )->whereNotNull( 'stop_date' )->where( 'stop_date', '<', $stop_date )->count() ){
                $_notice[ 'type' ] = 'danger';
                $_notice[ 'message' ] = 'These values intersect with already existing record';
                return \Redirect::back()->withInput()->with( compact( '_notice' ) );
        }

        if( $subscription_revenue ){
            $subscription_revenue->fill( \Input::all() );
            $subscription_revenue->save();
        }
        else{
            $subscription_revenue = SubscriptionRevenue::create( \Input::all() );
        }

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

        return \Redirect::to( route( 'admin.subscriptions.show', $subscription_id ) )->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
        $u = Subscription::findOrFail( $id );

        $u->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

        return \Redirect::route( 'admin.subscriptions.index' )->with( compact( '_notice' ) );
    }

    public function deleteRevenueDo( $id ){
        $u = SubscriptionRevenue::findOrFail( $id );

        $u->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

        return \Redirect::back()->with( compact( '_notice' ) );
    }
}
