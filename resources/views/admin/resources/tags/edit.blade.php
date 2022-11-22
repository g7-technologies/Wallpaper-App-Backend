@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.tags.edit.do', $tag->id ? $tag->id : 0 ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $tag->id ? 'Editing' : 'Adding' }} tag {{ $tag->id ? $tag->name : '' }}</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "name" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Name</label>
				{{ Form::text( 'name', Input::old( 'name' ) ? Input::old( 'name' ) : $tag->name, [ 'class' => 'form-control' ] ) }}
			</p>
		</div>
	</div>
	<hr/>
	<div class='row'>
		<div class='col-lg-8'>
			{{ Form::submit( 'Submit', [ 'class' => 'btn btn-primary', 'id' => 'notify-btn' ] ) }}
		</div>
	</div>
	{{ Form::close() }}
@stop