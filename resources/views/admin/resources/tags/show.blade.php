@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for tag</h3>
<div class='row'>
	<div class='col-lg-9'>
		<span class='tag'>Name</span>
		{{ $tag->name }}<br/>
		<span class='tag'>Created</span>
		{{ $tag->created_at }}<br/>
	</div>
</div>

<hr>

<a class='btn btn-primary' href="{{ route( 'admin.tags.edit', $tag->id ) }}">Edit</a>
<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.tags.delete.do', $tag->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>

	@include( 'admin.include.modals.delete' )
@stop