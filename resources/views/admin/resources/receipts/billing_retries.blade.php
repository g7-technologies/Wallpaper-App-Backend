@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Billing Retries ({{ $billing_retries_count }} total)</h3>

@forelse( $billing_retries as $br )
	<div class='row bottom-buffer'>
	    <div class='col-lg-3'>
	    	@if( $user = $br->user() )
		    	@include( 'admin.include.snippets.user', [ 'user' => $user ] )
		    	<a href="{{ route( 'admin.receipts.show', $br->receipt_id ) }}" class="badge badge-primary">receipt</a>
	    	@endif
	    </div>
	    <div class='col-lg-9'>
	    	@include( 'admin.include.snippets.renewal_info', [ 'ri' => $br ] )
	    </div>
	</div>
@empty
	No billing retries registered yet
@endforelse

<div class='row text-center'>
    <div class='col-xs-12'>
        {{ $billing_retries->appends( Input::except( 'page' ) )->links() }}
    </div>
</div>

@stop