<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryQuote;

class AdminCategoryQuotesController extends Controller
{
    public function index(){
        $quotes = CategoryQuote::orderBy( 'id', 'DESC' )->paginate( 20 );

        return \View::make( 'admin.resources.category_quotes.index', compact( 'quotes' ) );
    }

    public function edit( $id ){
    	if( !$id )
    		$quote = new CategoryQuote;
    	else
    		$quote = CategoryQuote::findOrFail( $id );

    	return \View::make( 'admin.resources.category_quotes.edit', compact( 'quote' ) );
    }

    public function editDo( $id, Request $request ){
    	if( !$id )
    		$quote = NULL;
    	else
    		$quote = CategoryQuote::findOrFail( $id );

		$this->validate( $request, CategoryQuote::$rules );

		if( $quote ){
			$quote->fill( \Input::all() );
			$quote->save();
		}
		else{
			$quote = CategoryQuote::create( \Input::all() );
		}

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

        $cat_id = $quote->category_id;
        $quote = new CategoryQuote;
        $quote->category_id = $cat_id;
        return \View::make( 'admin.resources.category_quotes.edit', compact( 'quote' ) )->with( compact( '_notice' ) );
    	//return \Redirect::to( route( 'admin.category_quotes.index' ) )->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
        $u = CategoryQuote::findOrFail( $id );

        $u->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

        return \Redirect::route( 'admin.category_quotes.index' )->with( compact( '_notice' ) );
    }
}
