@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Subscriptions
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.subscriptions.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
	</div>
	<hr/>
		@forelse( $subscriptions as $s )
			<div class='row'>
				<div class='col-lg-6'>
					<span class='tag'>Product ID</span>
					{{ $s->product_id }}<br/>
					<div class='top-buffer'>
						<a href="{{ route( 'admin.subscriptions.show', $s->id ) }}" class="btn btn-primary">Details</a>
						<div class='inline-block'>
							<div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
						    <span class="caret"></span></button>
						    <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
						      <a class="dropdown-item" href="{{ route( 'admin.subscriptions.edit', $s->id ) }}" class="">Edit</a>
						      <div class="dropdown-divider"></div>
						      <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.subscriptions.delete.do', $s->id ) }}">Delete</span>
						    </div>
						  </div>
						</div>
					</div>
				</div>
				<div class='col-lg-6'>
					<span class='tag'>Product name</span>
					{{ $s->name }}<br/>
					<span class='tag'>Billing period</span>
					{{ App\Subscription::$billing_periods[ $s->billing_period ] }}<br/>
					@if( $s->has_trial )
						<span class='tag'>Trial period</span>
						{{ App\Subscription::$trial_periods[ $s->trial_period ] }}<br/>
					@endif
					<span class='tag'>Revenues records</span>
					{{ $s->subscriptionRevenues()->count() }}<br/>
				</div>
			</div>
			<hr/>
		@empty
			<p>No subscriptions at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $subscriptions->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop