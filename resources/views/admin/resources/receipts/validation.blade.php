@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for receipt</h3>
<div class='row'>
	@if( $user = $receipt->user )
	    <div class='col-lg-3'>
	    	@include( 'admin.include.snippets.user_big', [ 'user' => $user ] )
	    </div>
	    <div class='col-lg-9'>
			<span class='tag'>Receipt received at</span> {{ $receipt->created_at }}<br/>

			<h4 class='top-buffer'>Data from Apple validation endpoint</h4>

	        <span class='tag'>Result code</span> {{ @$response->getResultCode() }}<br/>

	        <span class='tag'>Bundle ID</span> {{ @$response->getBundleId() }}<br/>

	        <span class='tag'>App item ID</span> {{ @$response->getAppItemId() }}<br/>

	        <span class='tag'>Original purchase date</span> {{ @$response->getOriginalPurchaseDate() }}<br/>

	        @if( $actualReceipt = $response->getReceipt() )
	        	<h5 class='top-buffer'>Receipt information</h5>
        		@forelse( $response->getPurchases() as $inapp )
        			<p>
        				<h6>In-app purchase information</h6>
        				<span class='tag'>quantity</span> {{ $inapp[ 'quantity' ] }}<br/>
        				<span class='tag'>product id</span> {{ $inapp[ 'product_id' ] }}<br/>
        				<span class='tag'>transaction id</span> {{ $inapp[ 'transaction_id' ] }}<br/>
        				<span class='tag'>original transaction id</span> {{ $inapp[ 'original_transaction_id' ] }}<br/>
        				<span class='tag'>purchase date</span> {{ $inapp[ 'purchase_date' ] }}<br/>
        				<span class='tag'>original purchase date</span> {{ $inapp[ 'original_purchase_date' ] }}<br/>
        				<span class='tag'>expires date</span> {{ @$inapp[ 'expires_date' ] }}<br/>
        				<span class='tag'>cancellation date</span> {{ $inapp->getCancellationDate() }}<br/>
        				<span class='tag'>is trial period</span> {{ $inapp[ 'is_trial_period' ] }}<br/>
        			</p>
        		@empty
        			<p>no in-app purchases for this receipt</p>
        		@endforelse
	        @endif

	        @if( $latestReceipt = $response->getLatestReceipt() )
	        	<h5 class='top-buffer'>Latest receipt information</h5>
	        	@if( $latestReceipt == $receipt->receipt )
	        		<span class='tag'>Latest receipt</span> is the same<br/>
	        	@else
	        		<span class='tag'>Latest receipt</span> is different from the one saved in database. <span data-latest-receipt="{{ $latestReceipt }}" id="btn-validate" class="cursor badge badge-primary">Tap to validate</span><br/>
	        	@endif

	        	@if( $latestReceiptInfo = $response->getLatestReceiptInfo() )
	        		@forelse( $latestReceiptInfo as $inapp )
	        			<p>
	        				<h6>In-app purchase information</h6>
	        				<span class='tag'>quantity</span> {{ $inapp[ 'quantity' ] }}<br/>
	        				<span class='tag'>product id</span> {{ $inapp[ 'product_id' ] }}<br/>
	        				<span class='tag'>transaction id</span> {{ $inapp[ 'transaction_id' ] }}<br/>
	        				<span class='tag'>original transaction id</span> {{ $inapp[ 'original_transaction_id' ] }}<br/>
	        				<span class='tag'>purchase date</span> {{ $inapp[ 'purchase_date' ] }}<br/>
	        				<span class='tag'>original purchase date</span> {{ $inapp[ 'original_purchase_date' ] }}<br/>
	        				<span class='tag'>expires date</span> {{ @$inapp[ 'expires_date' ] }}<br/>
	        				<span class='tag'>cancellation date</span> {{ $inapp->getCancellationDate() }}<br/>
	        				<span class='tag'>is trial period</span> {{ $inapp[ 'is_trial_period' ] }}<br/>
	        			</p>
	        		@empty
	        			<p>no in-app purchases for this receipt</p>
	        		@endforelse
	        	@endif
	    	@endif

			@if( $pendingRenewalInfo = $response->getPendingRenewalInfo() )
	        	<h5 class='top-buffer'>Pending Renewals</h5>
	        	<p>
	        		@forelse( $pendingRenewalInfo as $info )
	        			<p>
	        				<h6>Subscription renewal information</h6>
	        				<span class='tag'>product id</span> {{ $info[ 'product_id' ] }}<br/>
	        				<span class='tag'>auto renew product id</span> {{ $info[ 'auto_renew_product_id' ] }}<br/>
	        				<span class='tag'>original transaction id</span> {{ $info[ 'original_transaction_id' ] }}<br/>
	        				<span class='tag'>auto-renew status</span>
							@if( $info[ 'auto_renew_status' ] )
								<span class='badge badge-success'>yes</span><br/>
							@else
								<span class='badge badge-danger'>no</span><br/>
							@endif
							@if( isset( $info[ 'expiration_intent' ] ) )
								<span class='tag'>expiration intent</span> {{ \App\RenewalInfo::getExpirationIntentInfo( $info[ 'expiration_intent' ] ) }}<br/>
							@endif
							@if( isset( $info[ 'is_in_billing_retry_period' ] ) )
		        				<span class='tag'>billing retry?</span>
								@if( $info[ 'is_in_billing_retry_period' ] )
									<span class='badge badge-danger'>yes</span><br/>
								@else
									no<br/>
								@endif
							@endif
	        			</p>
	        		@empty
	        			<p>no in-app purchases for this receipt</p>
	        		@endforelse
	        	</p>
	        @endif
	    </div>
    @endif
</div>
@stop

@section( 'scripts' )
<script>
    $(document).ready( function(){
    	$( '#btn-validate' ).click( function(){
			var $form = $('<form>', {
			    action: '{{ route( "admin.receipts.validate", $receipt->id ) }}',
			    method: 'post'
			});

			$('<input>').attr({
				type: "hidden",
				name: 'latest_receipt',
				value: $(this).data( 'latest-receipt' )
			}).appendTo( $form );

			$form.appendTo('body').submit();
    	} );
    } );
</script>
@stop