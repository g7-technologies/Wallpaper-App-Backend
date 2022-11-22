@extends( 'admin.layout' )

@section( 'content' )
    <div class='row'>
        <div class="col-lg-3">
        </div>
        <div class="col-lg-6">
            <img id='main-img' class='img-fluid' style='max-height: 70%;'>
        </div>
        <div class="col-lg-3">
        </div>
    </div>

    <div class='row top-buffer bottom-buffer'>
        <div class="col-lg-3 text-right">
            <span id='btn-prev' class='btn btn-primary cursor'>&lt; Prev</span>
        </div>
        <div class="col-lg-6 text-center">
            <span id='btn-del' class='btn btn-danger cursor'>Delete</span>
        </div>
        <div class="col-lg-3 text-left">
            <span id='btn-next' class='btn btn-primary cursor'>Next &gt;</span>
        </div>
    </div>
@stop

@section( 'scripts' )
<script>
    $(document).ready( function(){
        var cur_it = 0;
        var data = new Array(
            @foreach( $walls as $w )
                [ {{ $w->id }}, '{{ $w->imageFile->fullURL() }}' ],
            @endforeach
        );

        $('#main-img').attr( 'src', data[ cur_it ][ 1 ] );

        $( document ).on( 'keydown', function( event ) {
           if (event.keyCode == 37 ) {
               if( cur_it ){
                cur_it--;
                $('#main-img').attr( 'src', data[ cur_it ][ 1 ] );
               }
               event.preventDefault();
           }
           if (event.keyCode == 39 ) {
               cur_it++;
               $('#main-img').attr( 'src', data[ cur_it ][ 1 ] );
               event.preventDefault();
           }
           if (event.keyCode == 13 ) {
               $.post( '/admin/wallpapers/delete/' + data[ cur_it ][ 0 ] );
               cur_it++;
               $('#main-img').attr( 'src', data[ cur_it ][ 1 ] );
               event.preventDefault();
           }
        });
    } );
</script>
@stop