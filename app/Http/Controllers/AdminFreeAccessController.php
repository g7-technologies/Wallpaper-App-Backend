<?php

namespace App\Http\Controllers;

use \App\FreeAccess;
use Illuminate\Http\Request;

class AdminFreeAccessController extends Controller
{
    public function index(){
        $access_records = FreeAccess::orderBy( 'id', 'DESC' )->paginate( 20 );

        return \View::make( 'admin.resources.free_access.index', compact( 'access_records' ) );
    }

    public function show( $id ){
    	$access = FreeAccess::findOrFail( $id );

    	return \View::make( 'admin.resources.free_access.show', compact( 'access' ) );
    }

    public function edit( $id ){
    	if( !$id )
    		$access = new FreeAccess;
    	else
    		$access = FreeAccess::findOrFail( $id );

    	return \View::make( 'admin.resources.free_access.edit', compact( 'access' ) );
    }

    public function editDo( $id, Request $request ){
		\Input::merge( [ 
			'valid_till' => \Input::get( 'date', null ) . ' ' . \Input::get( 'time', null )
		 ] );

    	if( !$id )
    		$access = NULL;
    	else
    		$access = FreeAccess::findOrFail( $id );

    	$rules = FreeAccess::$rules;
		$this->validate( $request, $rules );

		if( $access ){
			$access->fill( \Input::all() );
			$access->save();
		}
		else{
			$access = FreeAccess::create( \Input::all() );
		}

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

    	return \Redirect::to( route( 'admin.free_access.show', $access->id ) )->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
    	$access = FreeAccess::findOrFail( $id );
    	$access->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Free access removed successfully';

    	return \Redirect::back()->with( compact( '_notice' ) );
    }
}
