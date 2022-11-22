<div class="modal fade" id="confirm-sync" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Please, confirm the start of synchronization
            </div>
            {{ Form::open( [ 'url' => 'to-be-replaced', 'id' => 'sync-form' ] ) }}
            <div class="modal-body">
                You are going to initiate a synchronization process. This might change some local data or data stored on the other end. If you are sure about what you are doing confirm your action.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="submit" value="Sync it!" class="btn btn-primary btn-ok">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $(document).ready( function(){
        $('.btn-sync').click( function(e) {
          $('#sync-form').attr( 'action', $(this).attr( "action-href" ) );
          $('#confirm-sync').modal('show');
        });
    } );
</script>