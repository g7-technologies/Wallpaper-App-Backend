<div class="modal fade" id="confirm-mailout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Please, confirm the mailout
            </div>
            {{ Form::open( [ 'url' => 'to-be-replaced', 'id' => 'mailout-form' ] ) }}
            <div class="modal-body">
                You are going to initiate a mailout. The system will  try to deliver a real email to a real address. Please, confirm your action.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="submit" value="Send it!" class="btn btn-primary btn-ok">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $(document).ready( function(){
        $('.btn-mailout').click( function(e) {
          $('#mailout-form').attr( 'action', $(this).attr( "action-href" ) );
          $('#confirm-mailout').modal('show');
        });
    } );
</script>