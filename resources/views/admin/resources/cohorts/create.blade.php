@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Create a cohort</h3>
	<hr/>
	{{ Form::open( [ 'url' => route( 'admin.cohorts.create.do' ), 'method' => 'post' ] ) }}
	<div class='row'>
		<div class='col-lg-6'>
			<span class='tag'>Registration</span><br/>
			{{ Form::text( 'reg_start', \Input::old( 'reg_start' ), [ 'id' => 'reg-start-datepicker', 'placeholder' => 'registered on or after', 'class' => 'form-control' ] ) }}<br/>

			{{ Form::text( 'reg_stop', \Input::old( 'reg_stop' ), [ 'id' => 'reg-stop-datepicker', 'placeholder' => 'registered on or before', 'class' => 'form-control' ] ) }}<br/>
		</div>
		<div class='col-lg-6'>
			<span class='tag'>Payments</span><br/>
			{{ Form::number( 'min_payments', \Input::old( 'min_payments' ), [ 'placeholder' => 'minimum payments made', 'class' => 'form-control' ] ) }}<br/>
			{{ Form::select( 'subscriptions[]', \App\Subscription::pluck('product_id','id')->toArray(), Input::old( 'subscriptions' ), [ 'class' => 'form-control', 'id' => 'subscriptions-selector', 'style' => 'width:100%', 'multiple' => true ] ) }}
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-12'>
			<span class='tag'>Cohort name</span><br/>
			{{ Form::text( 'name', \Input::old( 'name' ), [ 'placeholder' => 'enter detailed cohort name', 'class' => 'form-control' ] ) }}<br/>
		</div>
	</div>
	<div class='row'>
		<div class='col-lg-12'>
			<button type="submit" class="btn btn-primary">
				<i class="glyphicon glyphicon-search"></i> Create
			</button>
		</div>
	</div>
	{{ Form::close() }}

	@include( 'admin.include.modals.delete' )
@stop

@section( 'scripts' )
<script>
	$(document).ready( function(){
		$( "#reg-start-datepicker" ).datepicker( { "dateFormat": "yy-mm-dd" } );
		$( "#reg-stop-datepicker" ).datepicker( { "dateFormat": "yy-mm-dd" } );

		$( '#subscriptions-selector' ).select2( {
			placeholder: 'Activated subscription(s)',
			multiple: true,
			theme: 'bootstrap'
		} );
	} );
</script>
@stop