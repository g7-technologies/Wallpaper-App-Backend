<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppleOriginalTransaction extends Model
{
    protected $table = 'apple_original_transactions';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'user_id', 'original_transaction_id' ];

    public static $rules = [
    	'user_id' => 'required|integer',
        'original_transaction_id' => 'required'
    ];

    public function user(){
        return $this->belongsTo( 'App\User' );
    }

    public function cleanup(){
        return $this->forceDelete();
    }
}
