@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.free_access.edit.do', $access->id ? $access->id : 0 ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $access->id ? 'Editing' : 'Adding' }} free access record</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-10'>
			<p class='{{ $errors->has( "user_id" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>User</label>
				{{ Form::select( 'user_id', \App\User::getNamedList(), Input::old( 'user_id' ) ? Input::old( 'user_id' ) : $access->user_id, [ 'class' => 'form-control', 'placeholder' => 'Select a user', 'id' => 'user-selector', 'style' => 'width:100%' ] ) }}
			</p>
			<p class='{{ $errors->has( "valid_till" ) ? "has-error" : "" }}'>
				<label  class='control-label tag'>Valid till date:</label>
				{{ Form::text( 'date', \Input::old( 'date' ) ? date( 'Y-m-d', strtotime( \Input::old( 'date' ) ) ) : ( $access->valid_till ? date( 'Y-m-d', strtotime( explode( ' ', $access->valid_till )[ 0 ] ) ) : date( 'Y-m-d', time() + (7 * 24 * 60 * 60) ) ), [ 'id' => 'valid-till-datepicker', 'class' => 'form-control' ] ) }}

				<label  class='control-label tag'>and time:</label>
				{{ Form::text( 'time', \Input::old( 'time' ) ? date( 'Y-m-d', strtotime( \Input::old( 'time' ) ) ) : ( $access->valid_till ? explode( ' ', $access->valid_till )[ 1 ] : date( 'H:i' ) ), [ 'id' => 'valid-till-timepicker', 'class' => 'form-control' ] ) }}
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
		$( "#valid-till-datepicker" ).datepicker( { "dateFormat": "yy-mm-dd" } );
		$( "#valid-till-timepicker" ).timepicker({ timeFormat:"hh:mm" });

		$( '#user-selector' ).select2( {
			theme: 'bootstrap'
		} );
	} );
</script>
@stop