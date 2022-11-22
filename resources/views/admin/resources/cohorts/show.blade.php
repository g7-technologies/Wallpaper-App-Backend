@extends( 'admin.layout' )

@section( 'content' )
<h3 class='buffer-0 bottom-buffer'>Details for {{ $cohort->name }} cohort</h3>
<a href="{{ route( 'admin.cohorts.ltv', $cohort->id ) }}" class="btn btn-primary">LTV</a>
<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.cohorts.delete.do', $cohort->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>
<hr>
<div class='row'>
	<div class='col-lg-4'>
		<span class='tag'>Cohort name</span>
		{{ $cohort->name }}<br/>
		<span class='tag'>Created at</span>
		{{ $cohort->created_at }}<br/>
		<span class='tag'>Number of users</span>
		{{ $cohort->users()->count() }}<br/>
		<hr>
		<span class='tag'>Cohort currencies</span>
		<p>
			@foreach( $cohort->currencies() as $cur_info )
				{{ $cur_info->store_country ? $cur_info->store_country : 'not set' }} — {{ $cur_info->total }} users<br/>
			@endforeach
		</p>
	</div>
	<div class='col-lg-8'>
		@foreach( $cohort->users as $u )
			<a href="{{ route( 'admin.users.show', $u->id ) }}">{{ $u->name }} {{ $u->email }}</a><br/>
		@endforeach
	</div>
</div>

<hr>

<a href="{{ route( 'admin.cohorts.ltv', $cohort->id ) }}" class="btn btn-primary">LTV</a>
<span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.cohorts.delete.do', $cohort->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>

	@include( 'admin.include.modals.delete' )
@stop