@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.app_settings.edit.do', $app_settings->id ? $app_settings->id : 0 ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $app_settings->id ? 'Editing' : 'Adding' }} app settings</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "app_version_id" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Select app version</label>
				{{ Form::select( 'app_version_id', \App\AppVersion::getList(), Input::old( 'app_version_id' ) ? Input::old( 'app_version_id' ) : ( $app_settings->app_version_id ? $app_settings->app_version_id : null ), [ 'class' => 'form-control', 'id' => 'app-version-id-selector', 'style' => 'width:100%' ] ) }}
			</p>
			<p class='{{ $errors->has( "female_monetization" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Free mode</label><br/>
				{{ Form::hidden( 'free_mode', 0 ) }}
				{{ Form::checkbox( 'free_mode', true, Input::old( 'free_mode' ) ? Input::old( 'free_mode' ) : $app_settings->free_mode, [ 'class' => 'form-control' ] ) }}
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
		$( '#app-version-id-selector' ).select2( {
			theme: 'bootstrap'
		} );
	} );
</script>
@stop