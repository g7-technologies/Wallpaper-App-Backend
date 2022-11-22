@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for free access</h3>
<div class='row'>
	<div class='col-lg-3'>
		@include( 'admin.include.snippets.user_big', [ 'user' => $access->user ] )
	</div>
	<div class='col-lg-9'>
		<p>
			<span class='tag'>Valid till</span> {{ $access->valid_till }}<br/>
			<span class='tag'>Valid?</span>
			@if( Carbon::now( 'UTC') > Carbon::parse( $access->valid_till, 'UTC' ) )
				<span class='badge badge-danger'>no</span>
			@else
				<span class='badge badge-success'>yes</span>
			@endif
		</p>
	</div>
</div>

<hr>

<a class='btn btn-primary' href="{{ route( 'admin.free_access.edit', $access->id ) }}">Edit</a>
<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.subscriptions.delete.do', $access->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>

	@include( 'admin.include.modals.delete' )
@stop