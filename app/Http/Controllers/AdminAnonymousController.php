<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Anonymous;

class AdminAnonymousController extends Controller
{
    public function index(){
        $anonymous_records = Anonymous::orderBy( 'id', 'DESC' )->paginate( 20 );

        return \View::make( 'admin.resources.anonymous.index', compact( 'anonymous_records' ) );
    }

    public function deleteDo( $id ){
    	$anonymous = Anonymous::findOrFail( $id );
    	$anonymous->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record removed successfully';

    	return \Redirect::route( 'admin.anonymous.index' )->with( compact( '_notice' ) );
    }

    public function purge(){
        if( \App::environment( [ 'production' ] ) ){
            $_notice[ 'type' ] = 'danger';
            $_notice[ 'message' ] = 'Not on the production!';

            return \Redirect::back()->with( compact( '_notice' ) );
        }

        foreach( \App\Anonymous::all() as $a )
            $a->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Purged all records';

        return \Redirect::route( 'admin.anonymous.index' )->with( compact( '_notice' ) );
    }
}
