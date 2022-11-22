<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallpaper extends Model
{
    protected $table = 'wallpapers';

    protected $fillable = [
        'name',
        'paid',
        'image_file_id',
        'thumb_image_file_id',
        'video_file_id',
        'sort',
        'hash',
        'listed',
        'deleted',
    ];

    public static $rules = [
        'name'  => 'required|string:255',
        'paid' => 'required|boolean',
        'image_file_id' => 'integer|nullable',
        'thumb_image_file_id' => 'integer|nullable',
        'video_file_id' => 'integer|nullable',
        'listed' => 'boolean',
    ];

    public function discardImageFile(){
    	$this->image_file_id = null;
    	$this->save();
    }

    public function discardVideoFile(){
        $this->video_file_id = null;
        $this->save();
    }

    public function discardThumbImageFile(){
        $this->thumb_image_file_id = null;
        $this->save();
    }

    public function countHash(){
        $this->hash = md5(
            $this->name . '|' .
            implode( '|', $this->categories()->pluck( 'categories.id' )->toArray() ) . '|' .
            implode( '|', $this->tags()->pluck( 'tags.id' )->toArray() ) . '|' .
            ( $this->deleted ? 'deleted' : 'not deleted' ) . '|' .
            ( $this->listed ? 'listed' : 'not listed' ) . '|' .
            ( $this->paid ? 'paid' : 'free' ) . '|' .
            ( ( $image = $this->imageFile ) ? $image->public_url : '' ) .
            ( ( $video = $this->videoFile ) ? $video->public_url : '' ) );
        $this->save();
    }

    public function categories(){
        return $this->belongsToMany( '\App\Category', 'wallpapers_2_categories' );
    }

    public function imageFile(){
    	return $this->belongsTo( '\App\ImageFile' );
    }

    public function videoFile(){
        return $this->belongsTo( '\App\ImageFile' );
    }

    public function thumbImageFile(){
        return $this->belongsTo( '\App\ImageFile', 'thumb_image_file_id' );
    }

    public function tags(){
        return $this->belongsToMany('App\Tag','wallpapers_2_tags');
    }

    public function getNameplate(){
        return $this->name . ' ' . ( $this->paid ? '(paid)' : '(free)' );
    }

    public function cleanup(){
        $this->tags()->sync( [] );

    	if( $image = $this->imageFile ){
            $this->discardImageFile();
    		$image->cleanup();
        }

        if( $video = $this->videoFile ){
            $this->discardVideoFile();
            $video->cleanup();
        }

        if( $image = $this->thumbImageFile ){
            $this->discardThumbImageFile();
            $image->cleanup();
        }

        $this->deleted = true;
        $this->save();

        foreach( $this->categories as $c ){
            $c->wallpapers()->detach( $c->id );
            $c->countHash();
        }
        $this->countHash();
        
        //return $this->forceDelete();
    }
}
