@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.wallpapers.edit.do', $wallpaper->id ? $wallpaper->id : 0 ), 'files' => true ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $wallpaper->id ? 'Editing' : 'Adding' }} wallpaper</h3>
		</div>
	</div>

	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "name" ) ? "has-error" : "" }}'>
				<label class='tag'>Name</label><br/>
				{{ Form::text( 'name', $wallpaper->name, [ 'class' => 'form-control', 'placeholder' => 'Enter name', 'style' => 'width:100%' ] ) }}
			</p>
			<p class='{{ $errors->has( "categories" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Categories</label>
				{{ Form::select( 'categories[]', \App\Category::getNamedList(), Input::old( 'categories' ) ? Input::old( 'categories' ) : ( Input::has( 'categories' ) ? Input::get( 'categories' ) : $wallpaper->categories()->pluck( 'categories.id' )->toArray() ), [ 'class' => 'form-control', 'id' => 'category-selector', 'style' => 'width:100%', 'multiple' => true ] ) }}
			</p>
			<p class='{{ $errors->has( "tags" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Tags</label>
				<?php $uts = $wallpaper->tags()->pluck('tags.id')->toArray(); ?>
				{{ Form::select( 'tags[]', \App\Tag::pluck('name','id')->toArray(), Input::old( 'tags' ) ? Input::old( 'tags' ) : $uts, [ 'class' => 'form-control', 'id' => 'tags-selector', 'style' => 'width:100%', 'multiple' => true ] ) }}
			</p>
			<p class='{{ $errors->has( "paid" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Paid?</label><br/>
				{{ Form::hidden( 'paid', 0 ) }}
				{{ Form::checkbox( 'paid', true, Input::old( 'paid' ) ? Input::old( 'paid' ) : $wallpaper->paid, [ 'class' => 'form-control', 'id' => 'paid-checkbox' ] ) }}
			</p>
			<p class='{{ $errors->has( "listed" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Listed?</label><br/>
				{{ Form::hidden( 'listed', 0 ) }}
				{{ Form::checkbox( 'listed', true, Input::old( 'listed' ) ? Input::old( 'listed' ) : $wallpaper->listed, [ 'class' => 'form-control', 'id' => 'listed-checkbox' ] ) }}
			</p>
		</div>
		<div class='col-lg-3'>
			<p class='{{ $errors->has( "image" ) ? "has-error" : "" }}'>
				<label class='tag'>Wallpaper image</label><br/>
				{{ Form::file( 'image', [ 'id' => 'wallpaper-image', 'class' => 'upload-button' ] ) }}
				<label for="wallpaper-image">
					<span class="btn btn-outline-secondary btn-sure cursor"><i class="fa fa-upload" aria-hidden="true"></i><span class='left-buffer-10'>Select image</span></span>
				</label>
			</p>
			@if( $image = $wallpaper->imageFile )
				<img src="{{ $image->fullURL() }}" class="img-fluid">
			@endif
		</div>
		<div class='col-lg-3'>
			<p class='{{ $errors->has( "image" ) ? "has-error" : "" }}'>
				<label class='tag'>Video file</label><br/>
				{{ Form::file( 'video', [ 'id' => 'wallpaper-video', 'class' => 'upload-button' ] ) }}
				<label for="wallpaper-video">
					<span class="btn btn-outline-secondary btn-sure cursor"><i class="fa fa-upload" aria-hidden="true"></i><span class='left-buffer-10'>Select video</span></span>
				</label>
			</p>
			@if( $video = $wallpaper->videoFile )
				<video src="{{ $video->fullURL() }}" width="100%" controls autoplay></video>
			@endif
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
		$( '#category-selector' ).select2( {
			theme: 'bootstrap'
		} );

		$( '#tags-selector' ).select2( {
			placeholder: 'Select tag(s)',
			multiple: true,
			theme: 'bootstrap'
		} );

		$( '#category-selector' ).select2( {
			placeholder: 'Select categories',
			multiple: true,
			theme: 'bootstrap'
		} );
	} );
</script>
@stop