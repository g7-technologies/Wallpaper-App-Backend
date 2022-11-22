@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.subscriptions.edit.do', $subscription->id ? $subscription->id : 0 ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $subscription->id ? 'Editing' : 'Adding' }} subscription {{ $subscription->id ? $subscription->name : '' }}</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "name" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Product name</label>
				{{ Form::text( 'name', Input::old( 'name' ) ? Input::old( 'name' ) : $subscription->name, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "product_id" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Product ID (as in AppStore Connect console)</label>
				{{ Form::text( 'product_id', Input::old( 'product_id' ) ? Input::old( 'product_id' ) : $subscription->product_id, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "billing_period" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Select billing period</label>
				{{ Form::select( 'billing_period', \App\Subscription::$billing_periods, Input::old( 'billing_period' ) ? Input::old( 'billing_period' ) : ( $subscription->billing_period ? $subscription->billing_period : \App\Subscription::BILLING_WEEKLY ), [ 'class' => 'form-control', 'id' => 'billing-period-selector', 'style' => 'width:100%' ] ) }}
			</p>
			<p class='{{ $errors->has( "default_revenue" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Enter default revenue in USD</label>
				{{ Form::text( 'default_revenue', Input::old( 'default_revenue' ) ? Input::old( 'default_revenue' ) : $subscription->default_revenue, [ 'class' => 'form-control' ] ) }}
			</p>
		</div>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "has_trial" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Has Trial?</label><br/>
				{{ Form::hidden( 'has_trial', 0 ) }}
				{{ Form::checkbox( 'has_trial', true, Input::old( 'has_trial' ) ? Input::old( 'has_trial' ) : $subscription->has_trial, [ 'class' => 'form-control', 'id' => 'has-trial' ] ) }}
			</p>
			<p class='{{ $errors->has( "trial_period" ) ? "has-error" : "" }}' style='margin-top:48px;' id='trial-period'>
				<label class='control-label tag'>Select trial period</label>
				{{ Form::select( 'trial_period', \App\Subscription::$trial_periods, Input::old( 'trial_period' ) ? Input::old( 'trial_period' ) : ( $subscription->trial_period ? $subscription->trial_period : \App\Subscription::TRIAL_3_DAYS ), [ 'class' => 'form-control', 'id' => 'trial-period-selector', 'style' => 'width:100%' ] ) }}
			</p>
		</div>
	</div>
	<hr/>
	<div class='row'>
		<div class='col-lg-8'>
			{{ Form::submit( 'Submit', [ 'class' => 'btn btn-primary', 'id' => 'notify-btn' ] ) }}
		</div>
	</div>
	{{ Form::close() }}
@stop

@section( 'scripts' )
<script>
	$(document).ready( function(){
		$( '#billing-period-selector' ).select2( {
			theme: 'bootstrap'
		} );
		$( '#trial-period-selector' ).select2( {
			theme: 'bootstrap'
		} );
		if( $('#has-trial').is(":checked") == false ){
			$( '#trial-period' ).hide();
		}
		$('#has-trial').change(function() {
		    if($(this).is(":checked")) {
		    	$( '#trial-period' ).fadeIn();
		    }
		    else
		    	$( '#trial-period' ).fadeOut();
		});
	} );
</script>
@stop