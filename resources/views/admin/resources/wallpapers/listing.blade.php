@extends( 'admin.layout' )

@section( 'content' )
    <h3>Listing for category {{ $cat_name }}</h3>
    @foreach( $walls as $index => $w )
        @if( $index % 12 == 0 )
            <div class="row">
        @endif
            <div class="col-lg-1">
                <div id="wall-{{ $w->id }}">
                    @if( $thumb = $w->thumbImageFile )
                        @if( $img = $w->imageFile )
                            <a href="{{ $img->fullURL() }}" target="_blanc">
                        @endif
                            <img src="{{ $thumb->fullURL() }}" class='img-fluid'>
                        @if( $img )
                            </a>
                        @endif
                    @endif
                    <span class='badge badge-danger cursor' action-href="{{ route( 'admin.wallpapers.delete.do', $w->id ) }}" onclick="$.post($(this).attr('action-href'));$('#wall-'+{{ $w->id }}).fadeOut()">delete</span>
                </div>
            </div>
        @if( $index % 12 == 11 )
            </div>
        @endif        
    @endforeach
@stop

@section( 'scripts' )
<script>
    $(document).ready( function(){
    } );
</script>
@stop