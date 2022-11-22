@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for {{ $subscription->name }} subscription</h3>
<div class='row'>
	<div class='col-lg-6'>
		<span class='tag'>Product name</span>
		{{ $subscription->name }}<br/>
		<span class='tag'>Product ID</span>
		{{ $subscription->product_id }}<br/>
		<span class='tag'>Billing period</span>
		{{ App\Subscription::$billing_periods[ $subscription->billing_period ] }}<br/>
		@if( $subscription->has_trial )
			<span class='tag'>Trial period</span>
			{{ App\Subscription::$trial_periods[ $subscription->trial_period ] }}<br/>
		@endif
		<span class='tag'>Default revenue</span>
		${{ $subscription->default_revenue }}<br/>
	</div>
	<div class='col-lg-6'>
		<h4>Revenue information</h4>
		@forelse( $subscription->subscriptionRevenues()->orderBy( 'start_date', 'ASC' )->get() as $sr )
		<p>
			<span class='tag'>Currency</span>
			{{ $sr->currency }}<br/>
			<span class='tag'>Revenue</span>
			${{ $sr->revenue }}<br/>
			<span class='tag'>Valid period</span>
			{{ $sr->start_date }} — {{ $sr->stop_date ? $sr->stop_date : 'no end' }}<br/>
			<a class='badge badge-primary' href="{{ route( 'admin.subscriptions.edit_revenue', [ $subscription->id, $sr->id ] ) }}">edit</a> <span class='badge badge-danger cursor btn-delete' action-href="{{ route( 'admin.subscriptions.delete_revenue.do', $sr->id ) }}">delete</span>
		</p>
		@empty
			No information about revenue
		@endforelse
	</div>
</div>

<hr>

<a class='btn btn-primary' href="{{ route( 'admin.subscriptions.edit', $subscription->id ) }}">Edit</a>
<a class='btn btn-primary' href="{{ route( 'admin.subscriptions.edit_revenue', [ $subscription->id, 0 ] ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add Revenue Information</a>
<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.subscriptions.delete.do', $subscription->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>

	@include( 'admin.include.modals.delete' )
@stop