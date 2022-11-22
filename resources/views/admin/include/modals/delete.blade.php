<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Please, confirm the action
            </div>
            {{ Form::open( [ 'url' => 'to-be-replaced', 'id' => 'delete-form' ] ) }}
            <div class="modal-body">
                You are going to delete the record and all of its' related data. I.e when you delete a user you also delete all his orders, comments, etc. The deleted data cannot be restored. We need your confirmation to proceed.  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="submit" value="Delete" class="btn btn-danger btn-ok">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $(document).ready( function(){
        $('.btn-delete').click( function(e) {
          $('#delete-form').attr( 'action', $(this).attr( "action-href" ) );
          $('#confirm-delete').modal('show');
        });
    } );
</script>