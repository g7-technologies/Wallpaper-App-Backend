@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.categories.edit.do', $category->id ? $category->id : 0 ), 'files' => true ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $category->id ? 'Editing' : 'Adding' }} category</h3>
		</div>
	</div>

	<div class='row'>
		<div class='col-lg-8'>
			<p class='{{ $errors->has( "name" ) ? "has-error" : "" }}'>
				<label class='tag'>Name</label><br/>
				{{ Form::text( 'name', $category->name, [ 'class' => 'form-control', 'placeholder' => 'Enter name', 'style' => 'width:100%' ] ) }}
			</p>
			<p class='{{ $errors->has( "listed" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Listed?</label><br/>
				{{ Form::hidden( 'listed', 0 ) }}
				{{ Form::checkbox( 'listed', true, Input::old( 'listed' ) ? Input::old( 'listed' ) : $category->listed, [ 'class' => 'form-control', 'id' => 'listed-checkbox' ] ) }}
			</p>
		</div>
		<div class='col-lg-4'>
			@if( $image = $category->imageFile )
				<img src="{{ $image->fullURL() }}" class="img-fluid">
			@endif
			<p class='{{ $errors->has( "image" ) ? "has-error" : "" }}'>
				<label class='tag'>Category image</label><br/>
				{{ Form::file( 'image', [ 'id' => 'category-image', 'class' => 'upload-button' ] ) }}
				<label for="category-image">
					<span class="btn btn-outline-secondary btn-sure cursor"><i class="fa fa-upload" aria-hidden="true"></i><span class='left-buffer-10'>Select image</span></span>
				</label>
			</p>
		</div>
	</div>

	<div class='row top-buffer'>
		<div class='col-lg-6'>
			{{ Form::submit( 'Submit', [ 'class' => 'btn btn-primary', 'id' => 'notify-btn' ] ) }}
		</div>
	</div>
	
	{{ Form::close() }}
@stop

@section( 'scripts' )
<script>
	$(document).ready( function(){
	} );
</script>
@stop