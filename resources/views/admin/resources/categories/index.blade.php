@extends( 'admin.layout' )

@section( 'content' )
    <h3 class='buffer-0 inline-block'>Categories</h3>

    <div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
        <a class="btn btn-primary" href="{{ route( 'admin.categories.edit', 0 ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add</a>
    </div>

    <div style="display: inline-block; vertical-align: middle;margin-top: -8px; margin-left: 20px;">
        <a class="btn btn-primary" href="{{ route( 'admin.categories.sort' ) }}"><i class="fa fa-arrows-alt" aria-hidden="true" style="margin-right: 10px;"></i>Sorting</a>
    </div>

    <hr/>
        @forelse( $categories as $c )
            <div class='row'>
                <div class='col-md-2'>
                    @if( $image = $c->imageFile )
                        <img src="{{ $image->fullURL() }}" class="img-fluid">
                    @endif
                </div>
                <div class='col-md-10'>
                    <p>
                        <span class='tag'>Name</span> {{ $c->name }}<br/>
                        <span class='tag'>Wallpapers count</span> {{ $c->wallpapers()->where( 'deleted', false )->count() }}<br/>
                        <span class='tag'>Listed?</span>
                            @if( $c->listed )
                                <span class="badge badge-secondary">yes</span>
                            @else
                                <span class="badge badge-primary">no</span>
                            @endif
                        <br/>
                        <span class='tag'>Hash</span> {{ $c->hash }}<br/>
                    </p>
                    <a class="btn btn-primary" href="{{ route( 'admin.wallpapers.edit', [ 'id' => 0, 'categories[]' => $c->id ] ) }}"><i class="fa fa-plus" aria-hidden="true" style="margin-right: 10px;"></i>Add a wallpaper</a>
                    <a class="btn btn-primary" href="{{ route( 'admin.wallpapers.index', [ 'categories[]' => $c->id ] ) }}"><i class="fa fa-list" aria-hidden="true" style="margin-right: 10px;"></i>Wallpapers</a>
                    <a class="btn btn-primary" href="{{ route( 'admin.wallpapers.gallery', $c->id ) }}"><i class="fa fa-list" aria-hidden="true" style="margin-right: 10px;"></i>gallery</a>
                    <a class="btn btn-primary" href="{{ route( 'admin.categories.sort_wallpapers', $c->id ) }}"><i class="fa fa-arrows-alt" aria-hidden="true" style="margin-right: 10px;"></i>Sorting</a>
                    <div class='inline-block'>
                        <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
                        <span class="caret"></span></button>
                        <div class="dropdown-menu" role="menu" aria-labelledby="menu1">
                          <a class="dropdown-item" href="{{ route( 'admin.categories.edit', $c->id ) }}" class="">Edit</a>
                          <div class="dropdown-divider"></div>
                          <span class="dropdown-item btn-delete cursor" action-href="{{ route( 'admin.categories.delete.do', $c->id ) }}">Delete</span>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <hr/>
        @empty
            <p>No categories at the moment</p>
        @endforelse

    <div class='row text-center'>
        <div class='col-lg-12'>
            {{ $categories->appends( Input::except( 'page' ) )->links() }}
        </div>
    </div>

    @include( 'admin.include.modals.delete' )
@stop