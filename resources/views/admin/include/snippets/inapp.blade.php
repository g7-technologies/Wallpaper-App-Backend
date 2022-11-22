<div>
	<span class='tag'>Product ID</span> <span style="color:black;font-size:15px"></span> {{ $id }} <span class='tag'>Name</span> {{ $name }}<br/>
	@if( isset( $transaction_id ) )
		<span class='tag'>Transaction ID</span> {{ $transaction_id }}<br/>
	@endif
	@if( isset( $original_transaction_id ) )
		<span class='tag'>Original Transaction ID</span> {{ $original_transaction_id }}<br/>
	@endif
	<span class='tag'>Purchased</span> {{ $purchase_date }}<br/>
	@if( @$expires_date )
		<span class='tag'>Expires</span> {{ $expires_date }}<br/>
	@endif
	@if( isset( $is_trial_period ) )
		@if( $is_trial_period )
			<span class="badge badge-secondary">using trial period</span>
		@endif
	@endif
	@if( isset( $valid ) )
		<span class='tag'>valid?</span>
		@if( $valid )
			<span class="badge badge-success">yes</span>
		@else
			<span class="badge badge-danger">NOT VALID</span>
		@endif
	@endif
	<span class='tag'>refunded?</span>
	@if( isset( $refunded ) && $refunded )
		<span class="badge badge-danger">REFUNDED</span>
	@else
		no
	@endif
</div>