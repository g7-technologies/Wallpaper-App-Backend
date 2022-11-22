<div class='row'>
    <div class='col-lg-1'>
        @if( $avatar = $author->avatar )
            <a href="{{ route( 'admin.users.show', $author->id ) }}"><img src="{{ $avatar->public_url }}" class="round img-fluid"><br/></a>
        @endif
    </div>
    <div class='col-lg-3'>
        {{ $author->name }}<br/>
        {{ $m->created_at }}
    </div>
    <div class='col-lg-7'>
        @if( $m->type == \App\Message::TYPE_TEXT )
            {{ $m->wording }}
        @elseif( $m->type == \App\Message::TYPE_IMAGE )
            <img src="{{ asset( $m->url ) }}" style="max-width: 200px;">
        @elseif( $m->type == \App\Message::TYPE_GEO )
            <img src="{{ asset( $m->url ) }}" width="200px" onclick="if( $(this).attr( 'width' ) == '200px' ) $(this).attr( 'width', '600px' ); else $(this).attr( 'width', '200px' );">
        @elseif( $m->type == \App\Message::TYPE_GIPHY )
            <div style="width:100%;height:0;padding-bottom:41%;position:relative;"><iframe src="{{ $m->url }}" width="100%" height="100%" style="position:absolute" frameBorder="0" class="giphy-embed" allowFullScreen></iframe></div>
        @endif
    </div>
    <div class='col-lg-1'>
        <span class='btn btn-danger btn-delete cursor' action-href="{{ route( 'admin.chats.delete_message', $m->id ) }}"><i class='fa fa-trash'></i></span>
    </div>
</div>
<br/>