@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.app_versions.edit.do', $app_version->id ? $app_version->id : 0 ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $app_version->id ? 'Editing' : 'Adding' }} app version</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "start_version" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Start version</label>
				{{ Form::text( 'start_version', Input::old( 'start_version' ) ? Input::old( 'start_version' ) : $app_version->start_version, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "stop_version" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Stop version</label>
				{{ Form::text( 'stop_version', Input::old( 'stop_version' ) ? Input::old( 'stop_version' ) : $app_version->stop_version, [ 'class' => 'form-control' ] ) }}
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
		$( '#market-selector' ).select2( {
			theme: 'bootstrap'
		} );
	} );
</script>
@stop