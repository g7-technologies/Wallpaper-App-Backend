@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.subscriptions.edit_revenue.do', [ $subscription->id, $subscription_revenue->id ? $subscription_revenue->id : 0 ] ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $subscription_revenue->id ? 'Editing' : 'Adding' }} revenue information for {{ $subscription->product_id }}</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "country" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Enter currency</label><br/>
				known currencies (with user count): {{ \App\User::stringCurrencies() }}
				{{ Form::text( 'currency', Input::old( 'currency' ) ? Input::old( 'currency' ) : $subscription_revenue->currency, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "revenue" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Revenue in USD</label>
				{{ Form::text( 'revenue', Input::old( 'revenue' ) ? Input::old( 'revenue' ) : $subscription_revenue->revenue, [ 'class' => 'form-control' ] ) }}
			</p>
		</div>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "start_date" ) ? "has-error" : "" }}'>
				<label  class='control-label tag'>Valid from</label>
				{{ Form::text( 'start_date', \Input::old( 'start_date' ) ? date( 'Y-m-d', strtotime( \Input::old( 'start_date' ) ) ) : $subscription_revenue->start_date, [ 'id' => 'start-date-datepicker', 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "stop_date" ) ? "has-error" : "" }}'>
				<label  class='control-label tag'>Valid till</label>
				{{ Form::text( 'stop_date', \Input::old( 'stop_date' ) ? date( 'Y-m-d', strtotime( \Input::old( 'stop_date' ) ) ) : $subscription_revenue->stop_date, [ 'id' => 'stop-date-datepicker', 'class' => 'form-control' ] ) }}
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
		$( '#country-selector' ).select2( {
			theme: 'bootstrap'
		} );

		$( "#start-date-datepicker" ).datepicker( { "dateFormat": "yy-mm-dd" } );
		$( "#stop-date-datepicker" ).datepicker( { "dateFormat": "yy-mm-dd" } );
	} );
</script>
@stop