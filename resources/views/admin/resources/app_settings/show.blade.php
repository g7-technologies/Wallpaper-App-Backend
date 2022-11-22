@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>App Settings</h3>
<div class='row'>
	<div class='col-lg-9'>
		<span class='tag'>Payment mode:</span> {!! $app_settings->free_mode ? "<span class='badge badge-danger'>free mode</span>" : "<span class='badge badge-primary'>paid mode</span>" !!}
	</div>
</div>

<hr>

<a class='btn btn-primary' href="{{ route( 'admin.app_settings.edit' ) }}">Edit</a>
@stop