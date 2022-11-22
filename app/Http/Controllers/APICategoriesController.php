<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Cache;
use App\Wallpaper;

class APICategoriesController extends Controller
{
    static public function prepareCategoriesList( &$string_to_hash ){
        $categories = Category::where( 'deleted', false )->where( 'listed', true )->orderBy( 'sort', 'ASC' )->get();
        $to_ret = [];

        $string_to_hash = '';

        foreach( $categories as $c ) {
            $image = $c->imageFile;
            $cat_info = [
                'id' => $c->id,
                'name' => $c->name,
                'image_url' => $image ? $image->fullURL() : null,
                'paid' => $c->wallpapers()->where( 'deleted', false )->where( 'listed', true )->where( 'paid', false )->count() ? false : true,
                'wallpapers_count' => $c->wallpapers()->where( 'deleted', false )->where( 'listed', true )->count(),
                'wallpapers' => [],
                'hash' => $c->hash,
            ];

            $wallpapers = $c->wallpapers()->where( 'deleted', false )->where( 'listed', true )->orderBy( 'sort', 'ASC' )->orderBy( 'id', 'DESC' )->get();
            $cat_ws_hash = '';
            foreach( $wallpapers as $w ) {
                $imageFile = $w->imageFile;
                $videoFile = $w->videoFile;
                $thumbImageFile = $w->thumbImageFile;

                $w_info = [
                    'id' => $w->id,
                    'name' => $w->name,
                    'paid' => $w->paid ? true : false,
                    'tags' => $w->tags()->pluck( 'tags.id' )->toArray(),
                    'thumb_image_url' => $thumbImageFile ? $thumbImageFile->fullURL() : null,
                    'image_url' => $imageFile ? $imageFile->fullURL() : null,
                ];

                if( $videoFile )
                    $w_info[ 'video_url' ] = $videoFile->fullURL();

                $w_info[ 'hash' ] = $w->hash;
                $cat_ws_hash .= $w->hash;

                $cat_info[ 'wallpapers' ][] = $w_info;
            }

            $to_ret[] = $cat_info;
            $string_to_hash .= ( $c->hash . '|' . $cat_ws_hash );
        }

        $deleted_categories_ids = array_values( array_unique( array_merge( \App\Category::where( 'deleted', true )->pluck( 'id' )->toArray(), \App\Category::where( 'listed', false )->pluck( 'id' )->toArray() ) ) );
        $deleted_wallpapers_ids = array_values( array_unique( array_merge( \App\Wallpaper::where( 'deleted', true )->pluck( 'id' )->toArray(), \App\Wallpaper::where( 'listed', false )->pluck( 'id' )->toArray() ) ) );

        $string_to_hash .= implode( '|', $deleted_categories_ids );
        $string_to_hash .= implode( '|', $deleted_wallpapers_ids );

        return $to_ret;
    }

    public function list(){  
        /*if( $cache = Cache::where( 'key', 'categories-list' )->first() ){
            if( \Input::has( 'hash' ) ){
                if( \Input::get( 'hash' ) == $cache->hash ){
                    return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );              
                }
            }

            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'categories' => json_decode( $cache->content ), 'deleted_categories' => array_values( array_unique( array_merge( Category::where( 'deleted', true )->pluck( 'id' )->toArray(), Category::where( 'listed', false )->pluck( 'id' )->toArray() ) ) ), 'categories_hash' => $cache->hash ] );
        }*/

        $string_to_hash = '';
        $to_ret = self::prepareCategoriesList( $string_to_hash );
        $hash = md5( $string_to_hash );

        /*if( \Input::has( 'hash' ) ){
            if( \Input::get( 'hash' ) == $hash ){
                return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );                
            }
        }*/

    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'categories' => $to_ret, 'deleted_categories' => array_values( array_unique( array_merge( Category::where( 'deleted', true )->pluck( 'id' )->toArray(), Category::where( 'listed', false )->pluck( 'id' )->toArray() ) ) ), 'categories_hash' => $hash ] );
    }
}