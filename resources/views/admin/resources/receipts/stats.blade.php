@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Renewal stats</h3>
<hr>
<h4>{{ $active_subscriptions }} active subscriptions. Of them:</h4>
<p class='left-buffer'>
	{{ ( $active_subscriptions - $active_trial_subscriptions ) }} <span class='tag'>paid subscriptions</span><br/>
	{{ $active_trial_subscriptions }} <span class='tag'>trials subscriptions</span>
</p>
<hr>
<h4>{{ $billing_retry }} in billing retry</h4>
@if( $billing_retry )
	<a href="{{ route( 'admin.receipts.billing_retries' ) }}">More details</a>
@endif
<hr>
<h4>{{ $renewals_count }} enabled renewals. Forecast: </h4>
@foreach( $stats as $day => $renewal_array )
	<p>
		<h5 class='top-buffer top-space' >{{ $day }}</h5>
		@foreach( $renewal_array as $r )
			<div class="row top-buffer">
			    <div class='col-lg-3'>
			    	@include( 'admin.include.snippets.user', [ 'user' => \App\User::find( $r[ 'user_id' ] ) ] )
			    	<a href="{{ route( 'admin.receipts.show', $r[ 'receipt_id' ] ) }}" class="badge badge-primary">receipt</a>
			    </div>
			    <div class='col-lg-9'>
			    	@include( 'admin.include.snippets.inapp', [ 'id' => $r[ 'product_id' ], 'name' => $r[ 'name' ], 'purchase_date' => $r[ 'purchase_date' ], 'expires_date' => $r[ 'expires_date' ], 'is_trial_period' => $r[ 'is_trial_period' ], 'refunded' => $r[ 'refunded' ] ] )
			    </div>
			</div>
		@endforeach
	</p>
@endforeach
<hr>
<p>
</p>

@stop