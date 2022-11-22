@extends( 'admin.layout' )

@section( 'content' )
	{{ Form::open( [ 'url' => route( 'admin.category_quotes.edit.do', $quote->id ? $quote->id : 0 ) ] ) }}
	<div class='row'>
		<div class='col-lg-12'>
			<h3 class='buffer-0 bottom-buffer'>{{ $quote->id ? 'Editing' : 'Adding' }} a quote {{ $quote->id ? 'for ' . $quote->category->name : '' }}</h3>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-12'>
			<p class='{{ $errors->has( "quote" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Category</label>
				{{ Form::select( 'category_id', \App\Category::getNamedList(), Input::old( 'categories' ) ? Input::old( 'categories' ) : ( Input::has( 'categories' ) ? Input::get( 'categories' ) : $quote->category_id ), [ 'class' => 'form-control', 'id' => 'category-selector', 'style' => 'width:100%' ] ) }}
			</p>

			<p class='{{ $errors->has( "quote" ) ? "has-error" : "" }}'>
				<label class='control-label tag'>Quote</label>
				{{ Form::text( 'quote', Input::old( 'quote' ) ? Input::old( 'quote' ) : $quote->quote, [ 'class' => 'form-control' ] ) }}
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
		$( '#category-selector' ).select2( {
			placeholder: 'Select category',
			theme: 'bootstrap'
		} );
	} );
</script>
@stop