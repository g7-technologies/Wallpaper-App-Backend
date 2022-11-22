<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Category;
use App\CategoryQuote;
use App\Cache;
use App\Wallpaper;

class APIWallpapersController extends Controller
{
    static public function getQuote(){
        $now = \Carbon::now();
        $start = \Carbon::parse( '2020-01-01 00:00:00' );

        $diff_days = $start->diffInDays( $now );
        srand( $diff_days );
        
        $ids = \App\CategoryQuote::pluck( 'id' )->toArray();
        $picked_id = random_int( 0, sizeof( $ids ) - 1 );

        return \App\CategoryQuote::find( $ids[ $picked_id ] );
    }
    
    static public function prepareTagsList(){
        $tags = Tag::orderBy( 'id', 'ASC' )->get();
        $to_ret = [];

        $string_to_hash = '';

        foreach( $tags as $t ) {
            $to_ret[] = [
                'id' => $t->id,
                'name' => $t->name,
                'wallpapers_count' => $t->wallpapers()->count(),
            ];

            $string_to_hash .= ( $t->id . $t->name . '|' );
        }

        $hash = md5( $string_to_hash );

        return [ 'tags' => $to_ret, 'deleted_tags' => \App\Tag::where( 'deleted', true )->pluck( 'id' )->toArray(), 'tags_hash' => $hash ];
    }

    public function tags(){
        if( $cache = Cache::where( 'key', 'tags-list' )->first() ){
            if( \Input::has( 'hash' ) ){
                if( \Input::get( 'hash' ) == $cache->hash ){
                    return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );              
                }
            }

            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'tags' => json_decode( $cache->content ), 'deleted_tags' => \App\Tag::where( 'deleted', true )->pluck( 'id' )->toArray(), 'tags_hash' => $cache->hash ] );
        }

        $out = self::prepareTagsList();

        if( \Input::has( 'hash' ) ){
            if( \Input::get( 'hash' ) == $out[ 'tags_hash' ] ){
                return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
            }
        }

        return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', $out );
    }

    static public function prepareWallpapersList( $category ){
        $wallpapers = $category->wallpapers()->where( 'deleted', false )->where( 'listed', true )->orderBy( 'sort', 'ASC' )->get();
        $to_ret = [];

        foreach( $wallpapers as $w ) {
            $image = $w->imageFile;
            $videoFile = $w->videoFile;
            $thumbImage = $w->thumbImageFile;

            $w_info = [
                'id' => $w->id,
                'name' => $w->name,
                'paid' => $w->paid ? true : false,
                'tags' => $w->tags()->pluck( 'tags.id' )->toArray(),
                'thumb_image_url' => $thumbImage ? $thumbImage->fullURL() : null,
                'thumb_image_path' => null,
                'image_url' => $image ? $image->fullURL() : null,
            ];
            if( $videoFile )
                $w_info[ 'video_url' ] = $videoFile->fullURL();

            if( $w_count < 30 && $thumbImage )
                $w_info[ 'thumb_image_path' ] = \App\Helper\castUnderscores( $thumbImage->fullURL() );
            else
                unset( $w_info[ 'thumb_image_path' ] );

            $w_info[ 'hash' ] = $w->hash;

            $to_ret[] = $w_info;
            $w_count++;
        }

        return $to_ret;
    }

    static public function prepareWallpapersListByTag( $tag, &$string_to_hash ){
        $wallpapers = $tag->wallpapers()->where( 'deleted', false )->where( 'listed', true )->get();

        $to_ret = [];
        $string_to_hash = '';

        foreach( $wallpapers as $w ) {
            $image = $w->imageFile;
            $videoFile = $w->videoFile;
            $thumbImage = $w->thumbImageFile;

            $w_info = [
                'id' => $w->id,
                'name' => $w->name,
                'paid' => $w->paid ? true : false,
                'tags' => $w->tags()->pluck( 'tags.id' )->toArray(),
                'thumb_image_url' => $thumbImage ? $thumbImage->fullURL() : null,
                'image_url' => $image ? $image->fullURL() : null,
            ];
            if( $videoFile )
                $w_info[ 'video_url' ] = $videoFile->fullURL();

            $w_info[ 'hash' ] = $w->hash;

            $to_ret[] = $w_info;
            $string_to_hash .= $w->hash;
        }

        return $to_ret;
    }

    public function list(){
		$validation = \Validator::make( \Input::all(), [ 'category_id' => 'required|integer' ] );
        if( $validation->fails() ){
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
        }

        $category = Category::find( \Input::get( 'category_id' ) );
        if( !$category )
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], 'Category not found' );

        $to_ret = self::prepareWallpapersList( $category );

        if( \Input::has( 'hash' ) ){
            if( \Input::get( 'hash' ) == $category->hash ){
                return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );                
            }
        }

    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'wallpapers' => $to_ret, 'deleted_wallpapers' => array_values( array_unique( array_merge( $category->wallpapers()->where( 'deleted', true )->pluck( 'wallpapers.id' )->toArray(), $category->wallpapers()->where( 'listed', false )->pluck( 'wallpapers.id' )->toArray() ) ) ), 'wallpapers_hash' => $category->hash ] );
    }

    public function listByTag(){
        $validation = \Validator::make( \Input::all(), [ 'tag_id' => 'required|integer' ] );
        if( $validation->fails() ){
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
        }

        $tag = Tag::find( \Input::get( 'tag_id' ) );
        if( !$tag )
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], 'Tag not found' );

        $string_to_hash = '';
        $to_ret = self::prepareWallpapersListByTag( $tag, $string_to_hash );

        $hash = md5( $string_to_hash );

        if( \Input::has( 'hash' ) ){
            if( \Input::get( 'hash' ) == $hash ){
                return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );                
            }
        }

        return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'wallpapers' => $to_ret, 'wallpapers_hash' => $hash ] );
    }

    public function get(){
		$validation = \Validator::make( \Input::all(), [ 'wallpaper_id' => 'required|integer' ] );
        if( $validation->fails() ){
            return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['error'], \App\Helper\glueErrors( $validation ) );
        }

        $wallpaper = Wallpaper::find( \Input::get( 'wallpaper_id' ) );
        if( !$wallpaper )
        	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['access_denied'], 'No wallpaper found by this ID' );

        if( \Input::has( 'hash' ) ){
            if( \Input::get( 'hash' ) == $wallpaper->hash ){
                return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );     
            }
        }

        $image = $wallpaper->imageFile;
        $video = $wallpaper->videoFile;
        $thumbImage = $wallpaper->thumbImageFile;

        $w_info = [     'id' => $wallpaper->id,
                                'name' => $wallpaper->name,
                                'paid' => $wallpaper->paid ? true : false,
                                'tags' => $wallpaper->tags()->pluck( 'tags.id' )->toArray(),
                                'thumb_image_url' => $thumbImage ? $thumbImage->fullURL() : null,
                                'image_url' => $image ? $image->fullURL() : null,
                                'hash' => $wallpaper->hash ];
        if( $video )
            $w_info[ 'video_url' ] = $video->fullURL();

    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'wallpaper' => $w_info ] );
    }

    public function check(){
    	return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK' );
    }

    public function quote(){
        $cache_quote = Cache::where( 'key', 'quote-quote' )->first();
        $cache_name = Cache::where( 'key', 'quote-name' )->first();

        if( !$cache_quote || !$cache_name ){
            $quote = self::getQuote();
            if( !$cache_quote )
                $cache_quote = Cache::create( [ 'key' => 'quote-quote', 'content' => $quote->quote, 'hash' => time() ] );
            else{
                $cache_quote->content = $quote->quote;
                $cache_quote->save();
            }

            if( !$cache_name )
                $cache_name = Cache::create( [ 'key' => 'quote-name', 'content' => $quote->category->name, 'hash' => time() ] );
            else{
                $cache_name->content = $quote->category->name;
                $cache_name->save();
            }
        }

        return \Response::json( [ 'quote' => $cache_quote->content, 'name' => $cache_name->content ] );
        //return \App\Helper\APIAnswer( app('config')->get('app_logic')['api']['codes']['ok'], 'OK', [ 'quote' => $cache_quote->content, 'name' => $cache_name->content ] );
    }
}