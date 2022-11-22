<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NonConsumable;

class AdminNonConsumablesController extends Controller
{
    public function index(){
        $non_consumables = NonConsumable::orderBy( 'id', 'DESC' )->paginate( 20 );

        return \View::make( 'admin.resources.non_consumables.index', compact( 'non_consumables' ) );
    }

    public function show( $id ){
    	$non_consumable = NonConsumable::findOrFail( $id );

    	return \View::make( 'admin.resources.non_consumables.show', compact( 'non_consumable' ) );
    }

    public function edit( $id ){
    	if( !$id )
    		$non_consumable = new NonConsumable;
    	else
    		$non_consumable = NonConsumable::findOrFail( $id );

    	return \View::make( 'admin.resources.non_consumables.edit', compact( 'non_consumable' ) );
    }

    public function editDo( $id, Request $request ){
    	if( !$id )
    		$non_consumable = NULL;
    	else
    		$non_consumable = NonConsumable::findOrFail( $id );

    	$rules = NonConsumable::$rules;
    	if( $non_consumable  ){	
    		if( $non_consumable->product_id == \Input::get( 'product_id', NULL ) )
    			unset( $rules[ 'product_id' ] );
    	}

		$this->validate( $request, $rules );

		if( $non_consumable ){
			$non_consumable->fill( \Input::all() );
			$non_consumable->save();
		}
		else{
			$non_consumable = NonConsumable::create( \Input::all() );
		}

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

    	return \Redirect::to( route( 'admin.non_consumables.show', $non_consumable->id ) )->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
        $u = NonConsumable::findOrFail( $id );

        $u->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

        return \Redirect::route( 'admin.non_consumables.index' )->with( compact( '_notice' ) );
    }
}
