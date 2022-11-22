@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for {{ $user->name }}</h3>
<div class='row'>
	<div class='col-lg-9'>
		<p>
			<span class='tag'>Email (assigned automatically)</span> {{ $user->email }}<br/>
			<span class='tag'>Role</span> {{ \App\User::$roles[ $user->role ] }}<br/>
			<span class='tag'>Tester?</span>
			@if( $user->tester )
				<span class='badge badge-secondary'>yes</span>
			@else
				no <a class='badge badge-primary' href="{{ route( 'admin.users.tester_mark', $user->id ) }}">mark as tester</a>
			@endif
			<br/>
			<hr/>
			<span class='tag'>Notification key</span> {!! $user->notification_key ? $user->notification_key : "not set" !!} @if( $user->notification_key ) <a class='left-buffer badge badge-secondary' href="{{ route( 'admin.push_notifications.send', $user->notification_key ) }}">send custom push notification</a> @endif<br/>
			<span class='tag'>IDFA</span> {!! $user->idfa ? $user->idfa : "not set" !!}<br/>
			<span class='tag'>Ad Tracking</span> {!! $user->ad_tracking === NULL ? "not set" : ( $user->ad_tracking ? "enabled" : "disabled" ) !!}<br/>
			<hr/>
			<span class='tag'>Registration</span> {{ $user->created_at }}<br/>
		    <span class='tag'>Version</span> {!! $user->version ? $user->version : "<span class='badge badge-danger'>not set</span>" !!}<br/>
		    <span class='tag'>Locale</span> {!! $user->locale ? $user->locale : "<span class='badge badge-danger'>not set</span>" !!}<br/>
		    <span class='tag'>Store country</span> {!! $user->store_country ?? "<span class='badge badge-danger'>not set</span>" !!}<br/>
		    <hr/>
		</p>
	</div>
</div>

<h3>Attribution</h3>
<div class='row'>
	<div class='col-lg-12'>
		<p>
			<span class='tag'>Random number</span> {{ $user->random_string ?? 'not set' }}
		</p>
	</div>
</div>
@if( $user->searchAdsInfo()->count() )
	@foreach( $user->searchAdsInfo as $sai )
		@include( 'admin.include.snippets.search_ads_info', compact( 'sai' ) )
	@endforeach
@endif
<hr>

<hr>
<h3>Login</h3>
<div class='row'>
	<div class='col-lg-12'>
		@foreach( $user->authTokens as $at )
			<p>
				<span class='tag'>Token</span> {{ $at->token }}
				<span class='badge badge-danger btn-delete cursor' action-href="{{ route( 'admin.users.delete_token', $at->id ) }}">Delete</span>
			</p>
		@endforeach
	</div>
</div>

<hr/>
<h3>Billing</h3>
@forelse( $user->receipts as $receipt )
	<p>
		<span class='tag'>Receipt</span> in {{ $receipt->receipt_type }}<br/>
		<a class='badge badge-primary' href="{{ route( 'admin.receipts.show', $receipt->id ) }}">Receipt details</a>
	</p>
	<h4>In-app purchases</h4>
	@forelse( $receipt->inAppPurchases()->orderBy( 'expires_date', 'DESC' )->get() as $inapp )
	<p>
		@if( $s = $inapp->subscription )
			@include( 'admin.include.snippets.inapp', [ 'id' => $s->product_id, 'name' => $s->name, 'purchase_date' => $inapp->purchase_date, 'expires_date' => $inapp->expires_date, 'is_trial_period' => $s->has_trial ? $inapp->is_trial_period : null, 'valid' => $inapp->valid, 'transaction_id' => $inapp->transaction_id, 'original_transaction_id' => $inapp->original_transaction_id, 'refunded' => $inapp->refunded ] )
		@elseif( $s = $inapp->nonConsumable )
			@include( 'admin.include.snippets.inapp', [ 'id' => $s->product_id, 'name' => $s->name, 'purchase_date' => $inapp->purchase_date, 'expires_date' => $inapp->expires_date, 'is_trial_period' => null, 'valid' => $inapp->valid, 'transaction_id' => $inapp->transaction_id, 'original_transaction_id' => $inapp->original_transaction_id ] )
		@endif
	</p>
	@empty
		No in-app purchases for this receipt
	@endforelse

	<h4>Renewal information</h4>
    @forelse( $receipt->renewalInfo as $ri )
        @include( 'admin.include.snippets.renewal_info', [ 'ri' => $ri ] )
    @empty
        No renewal information registered
    @endif
@empty
	No receipts & payments registered yet
@endforelse


<hr>

<a class='btn btn-primary' href="{{ route( 'admin.users.edit', $user->id ) }}">Edit</a>
<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.users.delete.do', $user->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>

<br><br>
	@include( 'admin.include.modals.delete' )
@stop