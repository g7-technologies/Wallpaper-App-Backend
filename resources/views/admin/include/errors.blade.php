@if( isset( $errors ) && sizeof( $errors ) )
	<div class="alert alert-dismissible alert-danger top-buffer" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		Data validation errors:
		<ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
		</ul>
	</div>
@endif