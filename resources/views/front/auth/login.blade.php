@extends( 'front.layout' )
@section( 'content' )
    <div>
      <div>
        <h1>Login Section</h1>
        <p>
          This page is strictly for administrative purporses.<br/>
          You may try to login either as administrator or a client.
        </p>
      </div>
      <div>
        {{ Form::open( [ 'url' => route( 'front.auth.login.do' ) ] ) }}
        <p>
          <label class='tag'>Email:</label>
          {{ Form::text( 'email', Input::old( 'email' ), [ 'class' => 'form-control' ] ) }}
        </p>
        <p>
          <label class='tag'>Password:</label>
          {{ Form::password( 'password', [ 'class' => 'form-control' ] ) }}
        </p>
        <div>
          {{ Form::submit( 'Submit', [ 'class' => 'btn btn-primary' ] ) }}
        </div>
        {{ Form::close() }}
      </div>
    </div>
@stop