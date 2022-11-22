@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.non_consumables.edit.do', $non_consumable->id ? $non_consumable->id : 0 ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $non_consumable->id ? 'Editing' : 'Adding' }} non consumables {{ $non_consumable->id ? $non_consumable->name : '' }}</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "name" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Product name</label>
				{{ Form::text( 'name', Input::old( 'name' ) ? Input::old( 'name' ) : $non_consumable->name, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "product_id" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Product ID (as in AppStore Connect console)</label>
				{{ Form::text( 'product_id', Input::old( 'product_id' ) ? Input::old( 'product_id' ) : $non_consumable->product_id, [ 'class' => 'form-control' ] ) }}
			</p>
			<p class='{{ $errors->has( "default_revenue" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Enter default revenue in USD</label>
				{{ Form::text( 'default_revenue', Input::old( 'default_revenue' ) ? Input::old( 'default_revenue' ) : $non_consumable->default_revenue, [ 'class' => 'form-control' ] ) }}
			</p>
		</div>
		<div class='col-lg-6'>
			<p class='{{ $errors->has( "lifetime" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Lifetime?</label><br/>
				{{ Form::hidden( 'lifetime', 0 ) }}
				{{ Form::checkbox( 'lifetime', true, Input::old( 'lifetime' ) ? Input::old( 'lifetime' ) : $non_consumable->lifetime, [ 'class' => 'form-control', 'id' => 'has-trial' ] ) }}
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