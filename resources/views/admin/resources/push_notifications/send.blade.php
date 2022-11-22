@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.push_notifications.send.do' ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>Send custom push notification to the user</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			{{ Form::hidden( 'notification_key', $notification_key ) }}
			<p class='{{ $errors->has( "title" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>title</label>
				{{ Form::text( 'title', Input::old( 'title' ), [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "body" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>body</label>
				{{ Form::textarea( 'body', Input::old( 'body' ), [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "img_url" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>img_url</label>
				{{ Form::text( 'img_url', Input::old( 'img_url' ), [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "type" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>type (1 — paygate, 2 — quote of the day)</label>
				{{ Form::text( 'type', Input::old( 'type' ), [ 'class' => 'form-control' ] ) }}
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