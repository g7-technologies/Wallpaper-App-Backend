@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>App installs</h3>
	<hr/>
		@forelse( $app_installs as $ai )
			<div class='row'>
				<div class='col-lg-6'>
					<span class='tag'>Registered at</span>
					{{ $ai->created_at }}<br/>
					<span class='tag'>Version</span>
					{{ $ai->version }}
					<span class='tag'>Random String</span>
					{{ $ai->random_string ?? 'not set' }}
					<span class='tag'>IDFA</span>
					{{ $ai->idfa ?? 'not set' }}
					<div class='top-buffer'>
						<span class='badge badge-danger btn-delete cursor' action-href="{{ route( 'admin.app_installs.delete.do', $ai->id ) }}"><i class='fa fa-trash right-buffer-10'></i>Delete</span>
					</div>
				</div>
				@if( $ai->searchAdsInfo()->count() )
				<div class='col-lg-6'>
					@foreach( $ai->searchAdsInfo as $sai )
						@include( 'admin.include.snippets.search_ads_info', compact( 'sai' ) )
					@endforeach
				</div>
				@endif
			</div>
			<hr/>
		@empty
			<p>No app installs registered at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $app_installs->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop