<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'image_file_id',
        'sort',
        'hash',
        'listed',
        'deleted',
    ];

    public static $rules = [
        'name'  => 'required|string:255',
        'image_file_id' => 'integer|nullable',
        'listed' => 'boolean',
    ];

    public function discardImageFile(){
    	$this->image_file_id = null;
    	$this->save();
    }

    public function countHash(){
        $string_to_hash = $this->name . '|' .
            ( $this->deleted ? 'deleted' : 'not deleted' ) . '|' .
            ( $this->listed ? 'listed' : 'not listed' ) . '|' .
            ( ( $image = $this->imageFile ) ? $image->public_url : '|' );

        foreach( $this->wallpapers as $w )
            $string_to_hash .= $w->hash . '|';

        $this->hash = md5( $string_to_hash );
        $this->save();
    }

    public function imageFile(){
    	return $this->belongsTo( '\App\ImageFile' );
    }

    public function quotes(){
        return $this->hasMany( '\App\CategoryQuote' );
    }

    public function wallpapers(){
    	return $this->belongsToMany( '\App\Wallpaper', 'wallpapers_2_categories' );
    }

    public static function getNamedList(){
        $to_ret = [];
        $categories = self::where( 'deleted', false )->get();
        foreach( $categories as $c ){
            $to_ret[ $c->id ] = $c->name;
        }

        return $to_ret;
    }

    public function cleanup(){
    	if( $image = $this->imageFile )
    		$image->cleanup();

        foreach( $this->quotes as $q )
            $q->cleanup();

    	foreach( $this->wallpapers as $w )
    		$w->cleanup();

        $this->deleted = true;
        $this->save();
        //return $this->forceDelete();
    }

    public function forceCleanup(){
        if( $image = $this->imageFile )
            $image->cleanup();

        $this->wallpapers()->sync( [] );

        return $this->forceDelete();
    }
}
