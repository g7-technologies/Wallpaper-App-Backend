@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for {{ $non_consumable->name }} subscription</h3>
<div class='row'>
	<div class='col-lg-6'>
		<span class='tag'>Product name</span>
		{{ $non_consumable->name }}<br/>
		<span class='tag'>Product ID</span>
		{{ $non_consumable->product_id }}<br/>
		<span class='tag'>Lifetime</span>
		{{ $non_consumable->lifetime ? 'yes' : 'no' }}<br/>
		<span class='tag'>Default revenue</span>
		${{ $non_consumable->default_revenue }}<br/>
	</div>
</div>

<hr>

<a class='btn btn-primary' href="{{ route( 'admin.non_consumables.edit', $non_consumable->id ) }}">Edit</a>
<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.non_consumables.delete.do', $non_consumable->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>

	@include( 'admin.include.modals.delete' )
@stop