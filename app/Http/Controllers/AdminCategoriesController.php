<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Wallpaper;
use App\ImageFile;

class AdminCategoriesController extends Controller
{
    public function index(){
        $categories = Category::where( 'deleted', false )->orderBy( 'sort', 'ASC' )->paginate( 20 );

        return \View::make( 'admin.resources.categories.index', compact( 'categories' ) );
    }

    public function sort(){
        $categories = Category::where( 'deleted', false )->orderBy( 'sort', 'ASC' )->get();

        return \View::make( 'admin.resources.categories.sort', compact( 'categories' ) );
    }

    public function sortDo(){
        foreach( \Input::get( 'order' ) as $sort => $id ){
            $c = Category::find( $id );
            if( $c ){
                $c->sort = $sort;
                $c->save();
            }
        }
    }

    public function sortWallpapers( $category_id ){
        $category = Category::findOrFail( $category_id );
        $wallpapers = $category->wallpapers()->where( 'deleted', false )->orderBy( 'sort', 'ASC' )->get();

        return \View::make( 'admin.resources.categories.sort_wallpapers', compact( 'wallpapers' ) );
    }

    public function sortWallpapersDo(){
        foreach( \Input::get( 'order' ) as $sort => $id ){
            $w = Wallpaper::find( $id );
            if( $w ){
                $w->sort = $sort;
                $w->save();
            }
        }
    }
/*
    public function show( $id ){
    	$category = Category::findOrFail( $id );

    	return \View::make( 'admin.resources.categories.show', compact( 'category' ) );
    }
*/
    public function edit( $id ){
    	if( !$id )
    		$category = new Category;
    	else
    		$category = Category::findOrFail( $id );

    	return \View::make( 'admin.resources.categories.edit', compact( 'category' ) );
    }

    public function editDo( $id, Request $request ){
    	if( !$id )
    		$category = NULL;
    	else
    		$category = Category::findOrFail( $id );

        $rules = Category::$rules;
        if( \Input::has( 'image' ) || !$id )
            $rules = array_merge( $rules, [ 'image' => 'required|' . ImageFile::$rules[ 'image' ] ] );

		$this->validate( $request, $rules );

		if( $category ){
			$category->fill( \Input::all() );
			$category->save();
		}
		else{
			$category = Category::create( \Input::all() );
		}

        if( \Input::has( 'image' ) ){
            $res = \App\Helper\saveCustomFile( \Input::file( 'image' ), '/categories/' );
            if( !$res ){
                $_notice[ 'type' ] = 'warning';
                $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' ) . ', however the image hasn\'t been saved due to an error';

                $category->countHash();
                return \Redirect::to( route( 'admin.categories.index' ) )->with( compact( '_notice' ) );
            }

            if( $image = $category->imageFile )
                $image->cleanup();

            $image = ImageFile::create( [   'public_url' => $res[ 'path' ],
                                            'original_name' => $res[ 'original_name' ],
                                            'size' => $res[ 'size' ] ] );
            $image->uploadToCloud();
            $category->image_file_id = $image->id;
            $category->save();
        }

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

        $category->countHash();
    	return \Redirect::to( route( 'admin.categories.index' ) )->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
    	$category = Category::findOrFail( $id );

        $category->cleanup();

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

		return \Redirect::route( 'admin.categories.index' )->with( compact( '_notice' ) );
    }
}
