@if( $user_notice = isset( $_notice ) ? $_notice : Session::get( '_notice', null ) )
  <div class="alert alert-dismissible alert-{{ $user_notice[ 'type' ] }} font16 @if( isset( $class ) ) {{ $class }} @endif top-buffer" id="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
    {{ $user_notice[ 'message' ] }}
    @if( isset( $user_notice[ 'lines' ] ) )
    	<ul>
    	@foreach( $user_notice[ 'lines' ] as $line )
    		<li>{{ $line }}</li>
    	@endforeach
    	</ul>
    @endif
  </div>
@endif