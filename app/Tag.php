<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name', 'deleted'
    ];

    public static $admin_edit_rules = [
        'name' => 'required|string|max:255',
    ];

    public function wallpapers(){
        return $this->belongsToMany( 'App\Wallpaper','wallpapers_2_tags' );
    }

    public function cleanup(){
        $w_ids = $this->wallpapers()->pluck( 'wallpapers.id' )->toArray();
        $this->wallpapers()->sync( [] );

        foreach( \App\Wallpaper::whereIn( 'id', $w_ids )->get() as $w )
            $w->countHash();

        $this->deleted = true;
        $this->save();

        //return $this->forceDelete();
    }
}
