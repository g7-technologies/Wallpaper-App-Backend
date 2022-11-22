<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeAccess extends Model
{
    protected $table = 'free_access';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'user_id', 'valid_till' ];

    public static $rules = [
    	'user_id' => 'required|integer',
        'valid_till' => 'required|date'
    ];

    public function user(){
        return $this->belongsTo( 'App\User' );
    }

    public function cleanup(){
        return $this->forceDelete();
    }
}
