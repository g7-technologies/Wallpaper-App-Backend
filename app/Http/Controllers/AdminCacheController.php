<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Wallpaper;
use App\Cache;

class AdminCacheController extends Controller
{
    public function categoriesList(){
/*
        foreach( Wallpaper::where( 'hash', null )->get() as $w )
            $w->countHash();
*/
        $cache = Cache::where( 'key', 'categories-list' )->first();
        if( $cache )
            $cache->cleanup();

        $_notice = [];
        $string_to_hash = '';
        $categories_list = APICategoriesController::prepareCategoriesList( $string_to_hash );
        $categories_list_hash = md5( $string_to_hash );

        $categories_list_json = json_encode( $categories_list );


        $cache = Cache::where( 'key', 'categories-list' )->where( 'hash', $categories_list_hash )->first();
        if( $cache ){
            $_notice[ 'type' ] = 'warning';
            $_notice[ 'message' ] = 'Categories cache is relevant, left unchanged. ';
        }
        else{
            $cache = Cache::where( 'key', 'categories-list' )->first();
            if( !$cache ){
                $cache = Cache::create( [
                    'key' => 'categories-list',
                    'hash' => $categories_list_hash, 
                    'content' => $categories_list_json,
                ] );
            }
            else{
                $cache->hash = $categories_list_hash;
                $cache->content = $categories_list_json;
                $cache->save();
            }
        }

        $tags_list = APIWallpapersController::prepareTagsList();
        $tags_list_json = json_encode( $tags_list[ 'tags' ] );
        $cache = Cache::where( 'key', 'tags-list' )->where( 'hash', $tags_list[ 'tags_hash' ] )->first();
        if( $cache ){
            $_notice[ 'type' ] = 'warning';
            $_notice[ 'message' ] = @$_notice[ 'message' ] . 'Tags cache is relevant, left unchanged';
            return \Redirect::back()->with( compact( '_notice' ) );
        }
        else{
            $cache = Cache::where( 'key', 'tags-list' )->first();
            if( !$cache ){
                $cache = Cache::create( [
                    'key' => 'tags-list',
                    'hash' => $tags_list[ 'tags_hash' ],
                    'content' => $tags_list_json,
                ] );
            }
            else{
                $cache->hash = $tags_list[ 'tags_hash' ];
                $cache->content = $tags_list_json;
                $cache->save();
            }
        }

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Updated cache';
        return \Redirect::back()->with( compact( '_notice' ) );
    }

	public function index(){
		$wallpapers_raw = Wallpaper::where( 'deleted', false )->where( 'listed', true )->get();
		$wallpapers = [];
		$wallpapers_presel = [];

		foreach( $wallpapers_raw as $w ){
			$wallpapers[ $w->id ] = $w->getNameplate();
			if( !$w->paid )
				$wallpapers_presel[] = $w->id;
		}

        return \View::make( 'admin.resources.cache.index', compact( 'wallpapers', 'wallpapers_presel' ) );
	}

	public function indexDo(){
		$zip = new \ZipArchive();
		$filename = storage_path() . "/" . time() . "cache.zip";

		if( $zip->open( $filename, \ZipArchive::CREATE ) !== true ){
			$_notice[ 'type' ] = 'danger';
		    $_notice[ 'message' ] = 'Failed to open zip file on disk';
			return \Redirect::route( 'admin.index' )->with( compact( '_notice' ) );
		}

        $wallpapers_data = [];
        $wallpapers_hash = '';

        // Теги
        $tags = \App\Tag::orderBy( 'id', 'ASC' )->get();
        $tags_info = [];

        $tags_hash = '';
        foreach( $tags as $t ) {
            $tags_info[] = [
                'id' => $t->id,
                'name' => $t->name,
                'wallpapers_count' => $t->wallpapers()->count(),
            ];

            $tags_hash .= ( $t->id . $t->name . '|' );
        }
        $tags_hash = md5( $tags_hash );

        $tags_json = json_encode( [ '_data' => [ 'tags' => $tags_info, 'tags_hash' => $tags_hash ] ] );
        $zip->addFromString( "tags.json", $tags_json );

        // Обои
        $wallpapers_data = [];
        $wallpapers_hash = '';

        $categories = Category::where( 'deleted', false )->where( 'listed', true )->orderBy( 'sort', 'ASC' )->get();

        foreach( $categories as $c ) {
        	$image = $c->imageFile;

            $category_info = [
                'id' => $c->id,
                'name' => $c->name,
                'paid' => $c->wallpapers()->where( 'paid', false )->count() ? false : true,
                'image_url' => $image ? $image->fullURL() : null,
                'image_path' => null,
                'wallpapers_count' => $c->wallpapers()->where( 'deleted', false )->where( 'listed', true )->count(),
                'wallpapers' => [],
                'hash' => $c->hash,
            ];

            if( $image ){
            	$category_info[ 'image_path' ] = \App\Helper\castUnderscores( $image->fullLocalURL() );
            	$zip->addFile(  public_path() . $image->public_url, \App\Helper\castUnderscores( $image->fullLocalURL() ) );
            }
            else
            	unset( $category_info[ 'image_path' ] );

            $wallpapers = $c->wallpapers()->where( 'deleted', false )->where( 'listed', true )->orderBy( 'sort', 'ASC' )->orderBy( 'id', 'DESC' )->get();
            $wallpapers_info = [];

            $w_count = 0;
            $cat_ws_hash = '';
            foreach( $wallpapers as $w ) {
                $imageFile = $w->imageFile;
                $thumbImageFile = $w->thumbImageFile;
                $videoFile = $w->videoFile;

                $wallpaper_info = [
                    'id' => $w->id,
					'name' => $w->name,
					'paid' => $w->paid ? true : false,
					'tags' => $w->tags()->pluck( 'tags.id' )->toArray(),
					'thumb_image_url' => $thumbImageFile ? $thumbImageFile->fullURL() : null,
					'thumb_image_path' => null,
					'image_url' => $imageFile ? $imageFile->fullURL() : null,
					'image_path' => null,
					'hash' => $w->hash
                ];

                $cat_ws_hash .= $w->hash;

                if( $videoFile )
                    $wallpaper_info[ 'video_url' ] = $videoFile->fullURL();

                if( $w_count < 30 && $thumbImageFile ){
	            	$wallpaper_info[ 'thumb_image_path' ] = \App\Helper\castUnderscores( $thumbImageFile->fullLocalURL() );
	            	$zip->addFile(  public_path() . $thumbImageFile->public_url, \App\Helper\castUnderscores( $thumbImageFile->fullLocalURL() ) );
                }
                else
                	unset( $wallpaper_info[ 'thumb_image_path' ] );

/*
                if( $imageFile && in_array( $w->id, \Input::get( 'wallpapers', [] ) ) ){
                    $zip->addFile(  public_path() . $imageFile->public_url, \App\Helper\castUnderscores( $imageFile->fullURL() ) );
                    $wallpaper_info[ 'image_path' ] = \App\Helper\castUnderscores( $imageFile->fullURL() );
                }
                else
*/
                	unset( $wallpaper_info[ 'image_path' ] );

                $wallpapers_info[] = $wallpaper_info;
                $w_count++;
            }

            $category_info[ 'wallpapers' ] = $wallpapers_info;
            $wallpapers_data[] = $category_info;

            $wallpapers_hash .= ( $c->hash . '|' . $cat_ws_hash );
        }

        $deleted_categories_ids = array_unique( array_merge( \App\Category::where( 'deleted', true )->pluck( 'id' )->toArray(), \App\Category::where( 'listed', false )->pluck( 'id' )->toArray() ) );
        $deleted_wallpapers_ids = array_unique( array_merge( \App\Wallpaper::where( 'deleted', true )->pluck( 'id' )->toArray(), \App\Wallpaper::where( 'listed', false )->pluck( 'id' )->toArray() ) );

        $wallpapers_hash .= implode( '|', $deleted_categories_ids );
        $wallpapers_hash .= implode( '|', $deleted_wallpapers_ids );
        $wallpapers_hash = md5( $wallpapers_hash );

        $wallpapers_json = json_encode( [ '_data' => [ 'categories' => $wallpapers_data, 'categories_hash' => $wallpapers_hash ] ] );
        $zip->addFromString( "wallpapers.json", $wallpapers_json );
        $zip->close();

		$headers = [ 'Content-Type: application/zip' ];
		return \Response::download( $filename, 'cache.zip', $headers );
/*
		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Archive successfully created';
		return \Redirect::route( 'admin.index' )->with( compact( '_notice' ) );
*/
	}
}
