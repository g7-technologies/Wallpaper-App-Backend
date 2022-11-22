@extends( 'admin.layout' )

@section( 'content' )
	<h3 class='buffer-0 inline-block'>Anonymous records
	</h3>
	<hr/>
		@forelse( $anonymous_records as $a )
			<div class='row'>
				<div class='col-lg-3'>
					@include( 'admin.include.snippets.anonymous', [ 'anonymous' => $a ] )
				</div>
				<div class='col-lg-9'>
						<span class='tag'>Notification key</span>
						{{ $a->notification_key ?? 'not set' }} @if( $a->notification_key ) <a class='left-buffer badge badge-secondary' href="{{ route( 'admin.push_notifications.send', $a->notification_key ) }}">send custom push notification</a> @endif <br/>
						<span class='tag'>Random String</span>
						{{ $a->random_string ?? 'not set' }}<br/>
						<span class='tag'>Timezone</span>
						{{ $a->timezone ?? 'not set' }}<br/>
						<span class='tag'>Locale</span>
						{{ $a->locale ?? 'not set' }}<br/>
						<span class='tag'>Version</span>
						{{ $a->version ?? 'not set' }}<br/>
						<span class='tag'>Created at</span>
						{{ $a->created_at }}
					</p>
					<div class='top-buffer'>
						<span action-href="{{ route( 'admin.anonymous.delete.do', $a->id ) }}" class="btn btn-delete cursor btn-danger">Delete</span>
					</div>
				</div>
			</div>
			<hr/>
		@empty
			<p>No anonymous records at the moment</p>
		@endforelse

	<div class='row text-center'>
		<div class='col-xs-12'>
			{{ $anonymous_records->appends( Input::except( 'page' ) )->links() }}
		</div>
	</div>

	<span action-href="{{ route( 'admin.anonymous.purge.do' ) }}" class='badge badge-danger btn-delete cursor'>Purge</span></a><br/><br/><br/>

	@include( 'admin.include.modals.delete' )
@stop