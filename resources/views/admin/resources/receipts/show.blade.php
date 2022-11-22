@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for receipt</h3>
<div class='row'>
	@if( $user = $receipt->user )
	    <div class='col-lg-3'>
			@include( 'admin.include.snippets.user_big', [ 'user' => $user ] )
	    </div>
	    <div class='col-lg-9'>
	        <span class='tag'>Type</span> {{ $receipt->receipt_type }}<br/>

	        <span class='tag'>Bundle ID</span> {{ $receipt->bundle_id }}<br/>

	        <span class='tag'>App Version</span> {{ $receipt->app_version }}<br/>

	        <hr>
	        <h3>In-app purchases</h3>
	        @forelse( $receipt->inAppPurchases()->orderBy( 'expires_date', 'DESC' )->get() as $inapp )
	        <p class='bottom-buffer'>
				@if( $s = $inapp->subscription )
					@include( 'admin.include.snippets.inapp', [ 'id' => $s->product_id, 'name' => $s->name, 'purchase_date' => $inapp->purchase_date, 'expires_date' => $inapp->expires_date, 'is_trial_period' => $s->has_trial ? $inapp->is_trial_period : null, 'valid' => $inapp->valid, 'transaction_id' => $inapp->transaction_id, 'original_transaction_id' => $inapp->original_transaction_id, 'refunded' => $inapp->refunded ] )
				@endif
	        </p>
	        @empty
	        	No in-app purchases for this receipt
	        @endforelse

            <h3>Renewal information</h3>
            @forelse( $receipt->renewalInfo as $ri )
                @include( 'admin.include.snippets.renewal_info', [ 'ri' => $ri ] )
            @empty
                No renewal information registered
            @endif
	    </div>
    @endif
</div>

<hr>

<a class='btn btn-primary cursor' href="{{ route( 'admin.receipts.validate', $receipt->id ) }}">Validate</a>

<a class='btn btn-primary cursor' href="{{ route( 'admin.receipts.validate_raw', [ $receipt->id, 1 ] ) }}">Validate Raw</a>

<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.receipts.delete.do', $receipt->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>

	@include( 'admin.include.modals.delete' )
@stop