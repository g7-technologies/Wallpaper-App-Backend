@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>App Settings
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.app_versions.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add version</a>
		<a class="btn btn-primary" href="{{ route( 'admin.app_settings.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add settings</a>
	</div>
	<hr/>
		@forelse( $app_versions as $av )
			<div class='row'>
				<div class='col-lg-6'>
					<p>
						@if( $av->start_version )
							<span class='tag'>start version</span> {{ $av->start_version }}<br/>
						@endif
						@if( $av->stop_version )
							<span class='tag'>stop version</span> {{ $av->stop_version }}<br/>
						@endif
						@if( !$av->start_version && !$av->stop_version )
							<span class='tag'>version</span> <span class='badge badge-primary'>default</span><br/>
						@endif
					</p>
					@if( !$av->isDefault() )
						<div class='top-buffer'>
							<div class='inline-block'>
								<div class="dropdown">
							    <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
							    <span class="caret"></span></button>
							    <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
							      <a class="dropdown-item" href="{{ route( 'admin.app_versions.edit', $av->id ) }}" class="">Edit</a>
							      <div class="dropdown-divider"></div>
							      <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.app_versions.delete.do', $av->id ) }}">Delete</span>
							    </div>
							  </div>
							</div>
						</div>
					@endif
				</div>
				<div class='col-lg-6'>
					@if( $as = $av->appSettings )
						<span class='tag'>Payment mode:</span> {!! $as->free_mode ? "<span class='badge badge-danger'>free mode</span>" : "<span class='badge badge-primary'>paid mode</span>" !!}
						<div class='top-buffer'>
							<div class='inline-block'>
								<div class="dropdown">
							    <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
							    <span class="caret"></span></button>
							    <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
							      <a class="dropdown-item" href="{{ route( 'admin.app_settings.edit', $as->id ) }}" class="">Edit</a>
							      <div class="dropdown-divider"></div>
							      <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.app_settings.delete.do', $as->id ) }}">Delete</span>
							    </div>
							  </div>
							</div>
						</div>
					@else
						No App Settings at the moment
					@endif
				</div>
			</div>
			<hr/>
		@empty
			<p>No app versions at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $app_versions->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop