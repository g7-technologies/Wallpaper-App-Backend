<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallpaper;
use App\Category;
use App\ImageFile;

class AdminWallpapersController extends Controller
{
    public $search_flag, $search_count, $search;

    public function __construct(){
        $this->search_flag = false;
        $this->search_count = 0;
        $this->search = [];
    }

    public function applySearch( $wallpapers ){
        $this->search[ 'categories' ] = \Input::get( 'categories', [] );
        $this->search[ 'tags' ] = \Input::get( 'tags', [] );
        $this->search[ 'selected' ] = \Input::get( 'selected', [] );

        $wallpapers_ids = \DB::table('wallpapers')
                    ->select( [ 'wallpapers.id' ] );

        if( sizeof( $this->search[ 'categories' ] ) ){
            $w_temp_ids = [];
            $cats = Category::whereIn( 'id', $this->search[ 'categories' ] )->get();
            foreach( $cats as $c ){
                $w_temp_ids = array_merge( $w_temp_ids, $c->wallpapers()->pluck( 'wallpapers.id' )->toArray() );
            }

            $wallpapers_ids = $wallpapers_ids->whereIn( 'id', $w_temp_ids );
            $this->search_flag = true;
        }

        if( sizeof( $this->search[ 'tags' ] ) ){
            $filter_ids = [];
            foreach( \App\Tag::whereIn( 'id', $this->search[ 'tags' ] )->get() as $t ){
                $temp_ids = $t->wallpapers()->pluck( 'wallpapers.id' )->toArray();
                $filter_ids = array_unique( array_merge( $filter_ids, $temp_ids ) );
            }
            $wallpapers_ids = $wallpapers_ids->whereIn( 'id', $filter_ids );
            $this->search_flag = true;
        }
        
        $ids = $wallpapers_ids->pluck( 'id' )->all();

        if( sizeof( $this->search[ 'selected' ] ) ){
            $ids = $this->search[ 'selected' ];
            $this->search_flag = true;
        }

        $wallpapers = $wallpapers->whereIn( 'id', $ids )->orderBy( 'sort', 'asc' );
        $this->search_count = $wallpapers->count();

        return $wallpapers;
    }

    public function index(){
        $wallpapers = Wallpaper::where( 'deleted', false )->orderBy( 'sort', 'ASC' );
        $total_count = $wallpapers->count();
        $wallpapers = $this->applySearch( $wallpapers )->paginate( 40 );

        $search_flag = $this->search_flag;
        $search_count = $this->search_count;
        $search = $this->search;

        return \View::make( 'admin.resources.wallpapers.index', compact( 'wallpapers', 'search_flag', 'search_count', 'search', 'total_count' ) );
    }

    public function allDo(){
        switch( \Input::get( 'action' ) ){
            case 1: // make paid
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->paid = true;
                    $w->save();
                    $w->countHash();
                }
                break;

            case 2:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->paid = false;
                    $w->save();
                    $w->countHash();
                }
                break;

            case 3:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    if( $w->paid == false ){
                        $w->sort = 0;
                        $w->save();
                        $w->countHash();
                    }
                    elseif( !$w->sort ){
                        $w->sort = 1;
                        $w->save();
                        $w->countHash();
                    }
                }
                break;

            case 4:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->listed = false;
                    $w->save();
                    $w->countHash();
                }
                break;

            case 5:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->listed = true;
                    $w->save();
                    $w->countHash();
                }
                break;

            default:
                break;
        }

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Applied successfully';
        return \Redirect::back()->with( compact( '_notice' ) );
    }

    public function selectedDo(){   
        switch( \Input::get( 'action' ) ){
            case 1: // make paid
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->paid = true;
                    $w->save();
                    $w->countHash();
                }
                break;

            case 2:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->paid = false;
                    $w->save();
                    $w->countHash();
                }
                break;

            case 3:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->cleanup();
                }
                break;

            case 4:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->listed = false;
                    $w->save();
                    $w->countHash();
                }
                break;

            case 5:
                $wallpapers = Wallpaper::where( 'deleted', false );
                foreach( $this->applySearch( $wallpapers )->get() as $w ){
                    $w->listed = true;
                    $w->save();
                    $w->countHash();
                }
                break;

            default:
                break;
        }

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Applied successfully';
        return \Redirect::back()->with( compact( '_notice' ) );
    }
/*
    public function show( $id ){
    	$wallpaper = Wallpaper::findOrFail( $id );

    	return \View::make( 'admin.resources.wallpapers.show', compact( 'wallpaper' ) );
    }
*/
    public function edit( $id ){
    	if( !$id )
    		$wallpaper = new Wallpaper;
    	else
    		$wallpaper = Wallpaper::findOrFail( $id );

        //dd( $wallpaper->categories()->pluck( 'categories.id' )->toArray() );

    	return \View::make( 'admin.resources.wallpapers.edit', compact( 'wallpaper' ) );
    }

    public function editDo( $id, Request $request ){
    	if( !$id )
    		$wallpaper = NULL;
    	else
    		$wallpaper = Wallpaper::findOrFail( $id );

        $rules = Wallpaper::$rules;
        $rules = array_merge( $rules, [ 'categories' => 'required|array|min:1', 'categories.*' => 'integer' ] );
        if( \Input::has( 'image' ) || !$id )
            $rules = array_merge( $rules, [ 'image' => 'required|' . ImageFile::$rules[ 'image' ] ] );
        if( \Input::has( 'video' ) )
            $rules = array_merge( $rules, [ 'video' => 'required|' . ImageFile::$rules[ 'image' ] ] );

		$this->validate( $request, $rules );

		if( $wallpaper ){
			$wallpaper->fill( \Input::all() );
			$wallpaper->save();
		}
		else{
			$wallpaper = Wallpaper::create( \Input::all() );
		}

        if( \Input::has( 'tags' ) )
            $wallpaper->tags()->sync( \Input::get( 'tags', [] ) );

        $wallpaper->categories()->sync( \Input::get( 'categories', [] ) );

        if( \Input::has( 'image' ) ){
            $res = \App\Helper\saveCustomFile( \Input::file( 'image' ), '/wallpapers/' );
            if( !$res ){
                $_notice[ 'type' ] = 'warning';
                $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' ) . ', however the image hasn\'t been saved due to an error';

                $wallpaper->countHash();
                return \Redirect::to( route( 'admin.wallpapers.index', [ 'categories' =>$wallpaper->categories()->pluck( 'categories.id' )->toArray(), 'tags' => $wallpaper->tags()->pluck( 'tags.id' )->toArray() ] ) )->with( compact( '_notice' ) );
            }

            if( $image = $wallpaper->imageFile )
                $image->cleanup();
            if( $thumb_image = $wallpaper->thumbImageFile )
                $thumb_image->cleanup();

            $image = ImageFile::create( [   'public_url' => $res[ 'path' ],
                                            'original_name' => $res[ 'original_name' ],
                                            'size' => $res[ 'size' ] ] );
            $image->uploadToCloud();
            $wallpaper->image_file_id = $image->id;
            $thumb_path = \App\Helper\makeThumb(
                                public_path() . $image->public_url, 
                                '/wallpapers_thumbs/',
                                200 );

            $thumb_image = ImageFile::create( [   'public_url' => $thumb_path,
                                            'original_name' => basename( $thumb_path ),
                                            'size' => 0 ] );
            $thumb_image->uploadToCloud();
            $wallpaper->thumb_image_file_id = $thumb_image->id;

            $wallpaper->save();
        }

        if( \Input::has( 'video' ) ){
            $res = \App\Helper\saveCustomFile( \Input::file( 'video' ), '/wallpaper_videos/' );
            if( !$res ){
                $_notice[ 'type' ] = 'warning';
                $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' ) . ', however the video hasn\'t been saved due to an error';

                $wallpaper->countHash();
                return \Redirect::to( route( 'admin.wallpapers.index', [ 'categories' =>$wallpaper->categories()->pluck( 'categories.id' )->toArray(), 'tags' => $wallpaper->tags()->pluck( 'tags.id' )->toArray() ] ) )->with( compact( '_notice' ) );
            }

            if( $video = $wallpaper->videoFile )
                $video->cleanup();

            $video = ImageFile::create( [   'public_url' => $res[ 'path' ],
                                            'original_name' => $res[ 'original_name' ],
                                            'size' => $res[ 'size' ] ] );
            $video->uploadToCloud();
            $wallpaper->video_file_id = $video->id;
            $wallpaper->save();
        }

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

        $wallpaper->countHash();
        foreach( $wallpaper->categories as $c )
            $c->countHash();
        
    	return \Redirect::to( route( 'admin.wallpapers.index', [ 'categories' => $wallpaper->categories()->pluck( 'categories.id' )->toArray(), 'tags' => $wallpaper->tags()->pluck( 'tags.id' )->toArray() ] ) )->with( compact( '_notice' ) );
    }

    public function listing( $cat_id ){
        $cat = \App\Category::find( $cat_id );

        $cat_name = $cat->name;
        $walls = $cat->wallpapers()->where( 'deleted', false )->get();

        return \View::make( 'admin.resources.wallpapers.listing', compact( 'cat_name', 'walls' ) );
    }

    public function gallery( $cat_id ){
        $cat = \App\Category::find( $cat_id );

        $cat_name = $cat->name;
        $walls = $cat->wallpapers()->where( 'deleted', false )->get();

        return \View::make( 'admin.resources.wallpapers.gallery', compact( 'walls' ) );
    }

    public function deleteDo( $id ){
    	$wallpaper = Wallpaper::findOrFail( $id );

        $wallpaper->cleanup();

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

		return \Redirect::route( 'admin.wallpapers.index' )->with( compact( '_notice' ) );
    }
}
