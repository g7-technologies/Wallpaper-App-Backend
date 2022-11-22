@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.app_settings.edit.do' ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>Editing app settings</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "free_mode" ) ? "has-error" : "" }}'>
				{{ Form::hidden( 'free_mode', 0 ) }}
				<label class='control-label tag'>Free mode:</label><br/>
				{{ Form::checkbox( 'free_mode', true, Input::old( 'free_mode' ) ? Input::old( 'free_mode' ) : $app_settings->free_mode, [ 'class' => 'form-control', 'id' => 'free-mode' ] ) }}
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

@section( 'scripts' )
<script>
	$(document).ready( function(){
	} );
</script>
@stop