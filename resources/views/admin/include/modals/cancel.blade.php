<div class="modal fade" id="confirm-cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Please, confirm the order cancelation
            </div>
            {{ Form::open( [ 'url' => 'to-be-replaced', 'id' => 'cancel-form' ] ) }}
            <div class="modal-body">
                You are going to cancel the order. Please, confirm your intent
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                <input type="submit" value="Cancel order" class="btn btn-danger btn-ok">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $(document).ready( function(){
        $('.btn-cancel').click( function(e) {
          $('#cancel-form').attr( 'action', $(this).attr( "action-href" ) );
          $('#confirm-cancel').modal('show');
        });
    } );
</script>