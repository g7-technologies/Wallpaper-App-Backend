@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Tags
	</h3>
	<div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
		<a class="btn btn-primary" href="{{ route( 'admin.tags.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
	</div>
	<hr/>
		@forelse( $tags as $t )
			<div class='row'>
				<div class='col-lg-10'>
						<span class='tag'>Tag</span>
						{{ $t->name }}<br/>
						<span class='tag'>Wallpapers count</span>
						{{ $t->wallpapers()->count() }}<br/>
						<span class='tag'>Created</span>
						{{ $t->created_at }}<br/>
					</p>
					<div class='top-buffer'>
						<a href="{{ route( 'admin.wallpapers.index', [ 'tags' => [ $t->id ] ] ) }}" class="btn btn-primary"><i class="fa fa-list" aria-hidden="true" style="margin-right: 10px;"></i>Wallpeper list</a>
						<div class='inline-block'>
							<div class="dropdown">
						    <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
						    <span class="caret"></span></button>
						    <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
						      <a class="dropdown-item" href="{{ route( 'admin.tags.edit', $t->id ) }}" class="">Edit</a>
						      <div class="dropdown-divider"></div>
						      <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.tags.delete.do', $t->id ) }}">Delete</span>
						    </div>
						  </div>
						</div>
					</div>
				</div>
			</div>
			<hr/>
		@empty
			<p>No tags at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $tags->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	@include( 'admin.include.modals.delete' )
@stop