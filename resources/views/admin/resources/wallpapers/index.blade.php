@extends( 'admin.layout' )

@section( 'content' )
    <h3 class='buffer-0 inline-block'>Wallpapers
        @if( $search_flag )
            (found {{ $search_count }}/{{ $total_count }})
        @else
            {{ $total_count }} total
        @endif
    </h3>

    <div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
        <a class="btn btn-primary" href="{{ route( 'admin.wallpapers.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
    </div>
    <hr/>
    {{ Form::open( [ 'url' => route( 'admin.wallpapers.index' ), 'method' => 'get' ] ) }}
    <div class='row'>
        <div class='col-lg-4'>
            {{ Form::select( 'categories[]', \App\Category::getNamedList(), @$search[ 'categories' ], [ 'class' => 'form-control', 'placeholder' => 'Select categories', 'id' => 'category-selector', 'style' => 'width:100%', 'multiple' => true ] ) }}
        </div>
        <div class='col-lg-6'>
            {{ Form::select( 'tags[]', \App\Tag::pluck('name','id')->toArray(), @$search[ 'tags' ], [ 'class' => 'form-control', 'id' => 'tags-selector', 'style' => 'width:100%', 'multiple' => true ] ) }}
        </div>
        <div class='col-lg-2'>
            <button type="submit" class="btn btn-primary form-control">
                <i class="glyphicon glyphicon-search"></i> Search
            </button>
        </div>
    </div>
    {{ Form::close() }}
    <hr/>
    {{ Form::open( [ 'url' => route( 'admin.wallpapers.all.do' ), 'method' => 'POST' ] ) }}
    @foreach( \Input::get( 'categories', [] ) as $c_id )
        {{ Form::hidden( 'categories[]', $c_id ) }}
    @endforeach
    @foreach( \Input::get( 'tags', [] ) as $t_id )
        {{ Form::hidden( 'tags[]', $t_id ) }}
    @endforeach
    <div class='row'>
        <div class='col-md-4'>
        </div>
        <div class='col-lg-6'>
            <span class='tag'>Apply to all:</span><br/>
            {{ Form::select( 'action', [ 1 => 'Make paid', 2 => 'Make free', 3 => 'Move free on top', 4 => 'Unlist', 5 => 'Make listed' ], [], [ 'class' => 'form-control', 'id' => 'action-all-selector', 'style' => 'width:100%'] ) }}
        </div>
        <div class='col-lg-2'>
            <button type="submit" class="btn btn-primary form-control">
                <i class="glyphicon glyphicon-search"></i> Apply to all
            </button>
        </div>
    </div>
    {{ Form::close() }}
    <hr/>
    {{ Form::open( [ 'url' => route( 'admin.wallpapers.selected.do' ), 'method' => 'POST' ] ) }}
    @foreach( \Input::get( 'categories', [] ) as $c_id )
        {{ Form::hidden( 'categories[]', $c_id ) }}
    @endforeach
    @foreach( \Input::get( 'tags', [] ) as $t_id )
        {{ Form::hidden( 'tags[]', $t_id ) }}
    @endforeach
    <div class='row'>
        <div class='col-lg-4'>
            <span class='tag'>Select</span><br/>
            {{ Form::checkbox( 'select_all', true, false, [ 'class' => 'form-control cursor', 'id' => 'all-check', 'onclick' => "$( '.wallpaper-check' ).prop( 'checked', $(this).prop('checked') );" ] ) }}
        </div>
        <div class='col-lg-6'>
            <span class='tag'>Apply to the selected:</span><br/>
            {{ Form::select( 'action', [ 1 => 'Make paid', 2 => 'Make free', 3 => 'Delete', 4 => 'Unlist', 5 => 'Make listed' ], [], [ 'class' => 'form-control', 'id' => 'action-selector', 'style' => 'width:100%'] ) }}
        </div>
        <div class='col-lg-2'>
            <button type="submit" class="btn btn-primary form-control">
                <i class="glyphicon glyphicon-search"></i> Apply to selection
            </button>
        </div>
    </div>
    <hr/>
        @forelse( $wallpapers as $w )
            <div class='row'>
                <div class='col-md-1 cursor' onclick="$(this).find( '.wallpaper-check' ).prop( 'checked', !$(this).find( '.wallpaper-check' ).prop('checked') );">
                    {{ Form::checkbox( 'selected[]', $w->id, false, [ 'class' => 'form-control', 'class' => 'wallpaper-check', 'onclick' => "$(this).prop( 'checked', !$(this).prop('checked') );" ] ) }}
                </div>
                <div class='col-md-1 cursor' onclick="$(this).parent().find( '.wallpaper-check' ).prop( 'checked', !$(this).parent().find( '.wallpaper-check' ).prop('checked') );">
                    @if( $thumbImage = $w->thumbImageFile )
                        <span class='tag'>Thumb</span><br/>
                        <img src="{{ $thumbImage->fullURL() }}" class="img-fluid">
                        @if( $w->videoFile )
                            <br><span class="badge badge-primary">LIVE</span>
                        @endif
                    @endif
                </div>
{{--
                <div class='col-md-2'>
                    @if( $image = $w->imageFile )
                        <span class='tag'>Full image</span><br/>
                        <img src="{{ $image->fullURL() }}" class="img-fluid">
                    @endif
                </div>
--}}
                <div class='col-md-5'>
                    <p>
                        <span class='tag'>Name</span> {{ $w->name }}<br/>
                        <span class='tag'>Hash</span> {{ $w->hash }}<br/>
                        <span class='tag'>Paid?</span>
                            @if( $w->paid )
                                <span class="badge badge-secondary">yes</span>
                            @else
                                <span class="badge badge-primary">no</span>
                            @endif
                        <br/>
                        <span class='tag'>Listed?</span>
                            @if( $w->listed )
                                <span class="badge badge-secondary">yes</span>
                            @else
                                <span class="badge badge-primary">no</span>
                            @endif
                        <br/>
                        <span class='tag'>Tags</span>
                        @foreach( $w->tags as $index => $t )
                            {{ ( $index ? '/ ' : '' ) }}{{ $t->name }}
                        @endforeach
                        <br/>
                        <span class='tag'>Categories</span>
                        @foreach( $w->categories as $index => $c )
                            {{ ( $index ? '/ ' : '' ) }}{{ $c->name }}
                        @endforeach
                    </p>
                </div>
                <div class='col-md-4'>
                    <a class="btn btn-primary" href="{{ route( 'admin.wallpapers.edit', $w->id ) }}" class="">Edit</a> <span class="btn btn-danger btn-delete cursor" action-href="{{ route( 'admin.wallpapers.delete.do', $w->id ) }}">Delete</span>
                </div>
            </div>
            <hr/>
        @empty
            <p>No wallpapers at the moment</p>
        @endforelse
    {{ Form::close() }}
    <div class='row text-center'>
        <div class='col-lg-12'>
            {{ $wallpapers->appends( Input::except( 'page' ) )->links() }}
        </div>
    </div>

    @include( 'admin.include.modals.delete' )
@stop

@section( 'scripts' )
<script>
    $(document).ready( function(){
        $( '#category-selector' ).select2( {
            theme: 'bootstrap'
        } );

        $( '#action-selector' ).select2( {
            theme: 'bootstrap'
        } );

        $( '#action-all-selector' ).select2( {
            theme: 'bootstrap'
        } );

        $( '#tags-selector' ).select2( {
            placeholder: 'Select tag(s)',
            multiple: true,
            theme: 'bootstrap'
        } );
    } );
</script>
@stop