@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Non Consumables
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.non_consumables.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
	</div>
	<hr/>
		@forelse( $non_consumables as $nc )
			<div class='row'>
				<div class='col-lg-6'>
					<span class='tag'>Product ID</span>
					{{ $nc->product_id }}<br/>
					<div class='top-buffer'>
						<a href="{{ route( 'admin.non_consumables.show', $nc->id ) }}" class="btn btn-primary">Details</a>
						<div class='inline-block'>
							<div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
						    <span class="caret"></span></button>
						    <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
						      <a class="dropdown-item" href="{{ route( 'admin.non_consumables.edit', $nc->id ) }}" class="">Edit</a>
						      <div class="dropdown-divider"></div>
						      <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.non_consumables.delete.do', $nc->id ) }}">Delete</span>
						    </div>
						  </div>
						</div>
					</div>
				</div>
				<div class='col-lg-6'>
					<span class='tag'>Product name</span>
					{{ $nc->name }}<br/>
					<span class='tag'>Default revenue</span>
					{{ $nc->default_revenue }}<br/>
					<span class='tag'>Lifetime</span>
					{{ $nc->lifetime ? 'yes' : 'no' }}<br/>
				</div>
			</div>
			<hr/>
		@empty
			<p>No non consumables at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $non_consumables->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop