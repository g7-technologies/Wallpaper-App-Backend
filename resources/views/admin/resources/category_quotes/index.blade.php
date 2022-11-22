@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Quotes
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.category_quotes.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
	</div>
	<hr/>
		@forelse( $quotes as $q )
			<?php $category = $q->category; ?>
			<div class='row'>
				<div class='col-lg-1'>
					@if( $image = $category->imageFile )
                        <img src="{{ $image->fullURL() }}" class="img-fluid">
                    @endif
				</div>
				<div class='col-lg-11'>
					<span class='tag'>Category</span> {{ $category->name }}<br/>
					<span class='tag'>Text</span>
					{{ $q->quote }}<br/>
					<div class='top-buffer'>
						<a href="{{ route( 'admin.category_quotes.edit', $q->id ) }}" class="btn btn-primary">Edit</a>
						<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.category_quotes.delete.do', $q->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>
					</div>
				</div>
			</div>
			<hr/>
		@empty
			<p>No quotes at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $quotes->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop