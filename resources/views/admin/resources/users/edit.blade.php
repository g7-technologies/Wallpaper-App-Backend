@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.users.edit.do', $user->id ? $user->id : 0 ), 'files' => true ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $user->id ? 'Editing' : 'Adding' }} user {{ $user->id ? $user->name : '' }}</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-8'>
			<p class='{{ $errors->has( "email" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Email (assigned automatically)</label>
				{{ Form::text( 'email', Input::old( 'email' ) ? Input::old( 'email' ) : $user->email, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "role" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Select role</label>
				{{ Form::select( 'role', \App\User::$roles, Input::old( 'role' ) ? Input::old( 'role' ) : ( $user->role ? $user->role : \App\User::ROLE_USER ), [ 'class' => 'form-control', 'placeholder' => 'Select role', 'id' => 'role-selector', 'style' => 'width:100%' ] ) }}
			</p>
			<p class='{{ $errors->has( "notification_key" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Notification key</label>
				{{ Form::text( 'notification_key', Input::old( 'notification_key' ) ? Input::old( 'notification_key' ) : $user->notification_key, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "version" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Version</label>
				{{ Form::text( 'version', Input::old( 'version' ) ? Input::old( 'version' ) : $user->version, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "store_country" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Currency</label>
				{{ Form::text( 'store_country', Input::old( 'store_country' ) ? Input::old( 'store_country' ) : $user->store_country, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "locale" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Locale</label>
				{{ Form::text( 'locale', Input::old( 'locale' ) ? Input::old( 'locale' ) : $user->locale, [ 'class' => 'form-control' ] ) }}
			</p>
		</div>
	</div>
	<hr/>
	<div class='row'>
		<div class='col-lg-8'>
			<p class='{{ $errors->has( "timezone" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Time zone</label>
				{{ Form::text( 'timezone', Input::old( 'timezone' ) ? Input::old( 'timezone' ) : $user->timezone, [ 'class' => 'form-control' ] ) }}
			</p>
		</div>
	</div>
	<hr/>
	<div class='row'>
		<div class='col-lg-8'>
			<p class='{{ $errors->has( "password" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Password</label>
				{{ Form::password( 'password', [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "password" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Password confirmation</label>
				{{ Form::password( 'password_confirmation', [ 'class' => 'form-control' ] ) }}
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
		$( '#role-selector' ).select2( {
			theme: 'bootstrap'
		} );
	} );
</script>
@stop