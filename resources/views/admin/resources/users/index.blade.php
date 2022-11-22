@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Users
		@if( $search_flag )
			(found {{ $search_count }}/{{ $total_count }})
		@else
			{{ $total_count }} total
		@endif
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.users.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
	</div>
	<hr/>
	{{ Form::open( [ 'url' => route( 'admin.users.index' ), 'method' => 'get' ] ) }}
	<div class='row'>
		<div class='col-lg-6'>
			{{ Form::text( 'search_name', @$search[ 'name' ], [ 'placeholder' => 'by name', 'class' => 'form-control' ] ) }}
		</div>
		<div class='col-lg-4'>
			{{ Form::number( 'search_payments_count', @$search[ 'payments_count' ], [ 'placeholder' => 'by payments count (no less than)', 'class' => 'form-control' ] ) }}
		</div>
		<div class='col-lg-2'>
			<button type="submit" class="btn btn-primary">
				<i class="glyphicon glyphicon-search"></i> Search
			</button>
		</div>
	</div>
	{{ Form::close() }}
	<hr/>
		@forelse( $users as $u )
			<div class='row'>
				<div class='col-lg-3'>
					@include( 'admin.include.snippets.user', [ 'user' => $u ] )
				</div>
				<div class='col-lg-9'>
						<span class='tag'>Role</span>
						{{ App\User::$roles[ $u->role ] }}<br/>
						<span class='tag'>Registration</span>
						{{ $u->created_at }}
					</p>
					<div class='top-buffer'>
						<a href="{{ route( 'admin.users.show', $u->id ) }}" class="btn btn-primary">Details</a>
						<div class='inline-block'>
							<div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
						    <span class="caret"></span></button>
						    <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
						      <a class="dropdown-item" href="{{ route( 'admin.users.edit', $u->id ) }}" class="">Edit</a>
						      <div class="dropdown-divider"></div>
						      <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.users.delete.do', $u->id ) }}">Delete</span>
						    </div>
						  </div>
						</div>
					</div>
				</div>
			</div>
			<hr/>
		@empty
			<p>No users at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $users->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	<hr>

	<span action-href="{{ route( 'admin.users.purge.do' ) }}" class='badge badge-danger btn-delete cursor'>Purge</span></a><br/><br/><br/>

	@include( 'admin.include.modals.delete' )
@stop