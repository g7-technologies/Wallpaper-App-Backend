@extends( 'admin.layout' )

@section( 'content' )
    <h3 class='buffer-0 inline-block'>Cache generator</h3>

    {{ Form::open( [ 'url' => route( 'admin.cache.index.do' ), 'method' => 'POST' ] ) }}
    <hr/>
    {{--
    <div class='row'>
        <div class='col-md-12'>
            <p>
                <span class='tag'>Wallpapers</span>
                {{ Form::select( 'wallpapers[]', $wallpapers, $wallpapers_presel, [ 'class' => 'form-control', 'id' => 'wallpapers-selector', 'style' => 'width:100%', 'multiple' => true ] ) }}
            </p>
        </div>
    </div>
    <hr/>
    --}}
    {{ Form::submit( 'Create .zip', [ 'class' => 'btn btn-primary' ] ) }}
    {{ Form::close() }}

    <br/>
    <br/>
    <br/>
    <br/>

    @include( 'admin.include.modals.delete' )
@stop

@section( 'scripts' )
<script>
    $(document).ready( function(){
        $( '#wallpapers-selector' ).select2( {
            placeholder: 'Select wallpaper(s)',
            multiple: true,
            theme: 'bootstrap'
        } );
    } );
</script>
@stop