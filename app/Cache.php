<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    protected $table = 'cache';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'key', 'hash', 'content' ];

    public static $rules = [
    	'key' => 'required|string',
        'hash' => 'required|string',
        'content' => 'required|string'
    ];

    public function cleanup(){
        return $this->forceDelete();
    }
}
