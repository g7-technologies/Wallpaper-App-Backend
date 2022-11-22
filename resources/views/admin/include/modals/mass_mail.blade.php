<div class="modal fade" id="confirm-mass-mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Please, confirm the mass mail
            </div>
            {{ Form::open( [ 'url' => 'to-be-replaced', 'id' => 'mass-mail-form' ] ) }}
            <div class="modal-body">
                You are going to send one o more automatically generated emails. If you are sure about what you are doing confirm your action.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="submit" value="Mail it!" class="btn btn-primary btn-ok">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $(document).ready( function(){
        $('.btn-mass-mail').click( function(e) {
          $('#mass-mail-form').attr( 'action', $(this).attr( "action-href" ) );
          $('#confirm-mass-mail').modal( 'show' );
        });
    } );
</script>