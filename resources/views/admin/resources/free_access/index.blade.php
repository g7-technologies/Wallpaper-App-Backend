@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Free Access 
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.free_access.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
	</div>
	<hr/>
		@forelse( $access_records as $a )
			<div class='row'>
				<div class='col-lg-3'>
					@if( $user = $a->user )
						@include( 'admin.include.snippets.user', [ 'user' => $user ] )
	                @else
	                	no user specified
	                @endif
				</div>
				<div class='col-lg-9'>
					<p>
						<span class='tag'>Valid till</span> {{ $a->valid_till }}<br/>
						<span class='tag'>Valid?</span>
						@if( Carbon::now( 'UTC') > Carbon::parse( $a->valid_till, 'UTC' ) )
							<span class='badge badge-danger'>no</span>
						@else
							<span class='badge badge-success'>yes</span>
						@endif
					</p>
					<div class='top-buffer'>
						<div class='inline-block'>
							<div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
						    <span class="caret"></span></button>
						    <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
						      <a class="dropdown-item" href="{{ route( 'admin.free_access.edit', $a->id ) }}" class="">Edit</a>
						      <div class="dropdown-divider"></div>
						      <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.free_access.delete.do', $a->id ) }}">Delete</span>
						    </div>
						  </div>
						</div>
					</div>
				</div>
			</div>
			<hr/>
		@empty
			<p>No access records at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $access_records->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop