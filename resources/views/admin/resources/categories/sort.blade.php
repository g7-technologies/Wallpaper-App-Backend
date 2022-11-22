@extends( 'admin.layout' )

@section( 'styles' )
  <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 800px; }
  #sortable li { margin: 10px 10px 10px 0; float: left; width: 200px; height: 250px; font-size: 15px; text-align: center; overflow: hidden; cursor: move;}
  </style>
@stop

@section( 'content' )
    <h3 class='buffer-0 inline-block'>Sorting categories</h3>

    <hr/>
    <div class='row'>
        <ul id="sortable">
            @foreach( $categories as $c )
            <li class="ui-state-default" data-id="{{ $c->id }}">
                @if( $image = $c->imageFile )
                    <img src="{{ $image->public_url }}" class="img-fluid" style="max-height:200px;"><br/>
                @endif
                {{ $c->name }}
            </li>
            @endforeach
        </ul>
    </div>

    @include( 'admin.include.modals.delete' )
@stop

@section( 'scripts' )
<script>
  $( function() {
    $( "#sortable" ).sortable({
        //axis: 'y',
        update: function (event, ui) {
            var order= [];
            $("#sortable li").each(function(i) {
                order.push( $(this).attr('data-id') );
            });

        $.post( '{{ route( "admin.categories.sort.do" ) }}', { order: order } )
            .success( function( data ){
            })
            .error(function(data) { 
            }); 
        }
    });
    $( "#sortable" ).disableSelection();
  } );
</script>
@stop