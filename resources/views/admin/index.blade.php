@extends( 'admin.layout' )

@section( 'content' )
        <h2>Events for {{ $day }}</h2>

    	<hr/>
        <div class='row'>
        	<div class='col-lg-4'>
                <a href="{{ route( 'admin.index' ) }}?day={{ date( 'Y-m-d', strtotime( $day ) - 60 * 60 * 24 ) }}" class="btn btn-primary"><i style="margin-right: 8px;" class="fa fa-angle-left" aria-hidden="true"></i>Day ago</a>
            </div>
        	<div class='col-lg-4'>
                {{ Form::open( [ 'url' => route( 'admin.index' ), 'method' => 'GET', 'id' => 'search-form' ] ) }}
    				{{ Form::text( 'day', $day, [ 'id' => 'day-picker', 'class' => 'form-control cursor' ] ) }}
                {{ Form::close() }}
        	</div>
        	@if( \Input::get( 'day', null ) != null && 
                \Input::get( 'day', null ) != date( 'Y-m-d' ) )
                <div class='col-lg-4 text-right'>
                    <a href="{{ route( 'admin.index' ) }}?day={{ date( 'Y-m-d', strtotime( $day ) + 60 * 60 * 24 ) }}" class='btn btn-primary'>Day forward<i style="margin-left: 8px;" class="fa fa-angle-right" aria-hidden="true"></i></a>
                </div>
            @endif
        </div>

        <hr/>

        @forelse( $notices as $n )
            @if( ( $user = $n->user ) )
            	<div class='row top-buffer'>
            		<div class='col-lg-3'>
                        @include( 'admin.include.snippets.user', [ 'user' => $user ] )
            		</div>
            		<div class='col-lg-8'>
                        <span class='tag'>Time</span> {{ explode( ' ', $n->created_at )[ 1 ] }} <br/>
                        <span class='tag'>Role</span> {{ \App\User::$roles[ $user->role ] }} <br/>
                        <span class='tag'>Notice</span> {{ $n->notice }}
        	    	</div>
            		<div class='col-lg-1 text-right'>
            			<label class='badge badge-primary btn-delete cursor' action-href="{{ route( 'admin.delete_notice.do', $n->id ) }}">X</label>
            		</div>
            	</div>
            	<hr/>
            @endif
        @empty
            <br/>
        	<p>Nothing happened</p>
        @endforelse

    @include( 'admin.include.modals.delete' )
@stop

@section( 'scripts' )
<script>
	$(function () {
		$( "#day-picker" ).datepicker( { 
            "dateFormat": "yy-mm-dd",
            onSelect: function() {
                $( '#search-form' ).submit();
            }
        } );
	});
</script>
@stop