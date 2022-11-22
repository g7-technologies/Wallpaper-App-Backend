<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Tag;

class AdminTagsController extends Controller
{
    public function index(){
        $tags = Tag::paginate( 20 );

        return \View::make( 'admin.resources.tags.index', compact( 'tags' ) );
    }

    public function show( $id ){
    	$tag = Tag::findOrFail( $id );

    	return \View::make( 'admin.resources.tags.show', compact( 'tag' ) );
    }

    public function edit( $id ){
    	if( !$id )
    		$tag = new Tag;
    	else
    		$tag = Tag::findOrFail( $id );

    	return \View::make( 'admin.resources.tags.edit', compact( 'tag' ) );
    }

    public function editDo( $id, Request $request ){
    	if( !$id )
    		$tag = NULL;
    	else
    		$tag = Tag::findOrFail( $id );

		$this->validate( $request, Tag::$admin_edit_rules );

		if( $tag ){
			$tag->fill( \Input::all() );
			$tag->save();
		}
		else{
			$tag = Tag::create( \Input::all() );
		}

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

    	return \Redirect::to( route( 'admin.tags.show', $tag->id ) )->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
    	$tag = Tag::findOrFail( $id );

        $tag->cleanup();

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

		return \Redirect::route( 'admin.tags.index' )->with( compact( '_notice' ) );
    }
}
