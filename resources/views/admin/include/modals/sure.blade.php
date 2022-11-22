<div class="modal fade" id="confirm-sure" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Are you sure?
            </div>
            {{ Form::open( [ 'url' => 'to-be-replaced', 'id' => 'sure-form' ] ) }}
            <div class="modal-body">
                Please, confirm your intent
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                <input type="submit" value="Proceed" class="btn btn-primary btn-ok">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $(document).ready( function(){
        $('.btn-sure').click( function(e) {
          $('#sure-form').attr( 'action', $(this).attr( "action-href" ) );
          $('#confirm-sure').modal('show');
        });
    } );
</script>