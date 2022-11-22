<!doctype html>
<html>
    <body style="font-family:sans-serif;">
    	@if( session( 'notice' ) )
    		<b>Notice: </b> {{ session( 'notice' ) }}
    		<br/><br/>
    	@endif

    	{{ \Form::open( [ 'url' => route( 'front.test.dummy.send' ) ] ) }}
        Send a test email to {{ \Form::text( 'email' ) }}
        <br>
        {{ \Form::submit( 'send' ) }}
        {{ \Form::close() }}
    </body>
</html>
