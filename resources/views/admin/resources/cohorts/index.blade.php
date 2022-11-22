@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Cohorts
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.cohorts.create' ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Create</a>
	</div>
	<hr/>
		@forelse( $cohorts as $c )
			<div class='row'>
				<div class='col-lg-10'>
						<span class='tag'>Cohort name</span>
						{{ $c->name }}<br/>
						<span class='tag'>Created at</span>
						{{ $c->created_at }}<br/>
					</p>
					<div class='top-buffer'>
						<a href="{{ route( 'admin.cohorts.show', $c->id ) }}" class="btn btn-primary">Details</a>
						<a href="{{ route( 'admin.cohorts.ltv', $c->id ) }}" class="btn btn-primary">LTV</a>
						<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.cohorts.delete.do', $c->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>
					</div>
				</div>
			</div>
			<hr/>
		@empty
			<p>No cohorts at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $cohorts->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop