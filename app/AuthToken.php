<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $table = 'auth_tokens';

	protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
	protected $fillable = ['token', 'valid', 'user_id' ];

	public $forceEntityHydrationFromInput = true;

    public $autoPurgeRedundantAttributes = true;

    public static function createToken( $user_id ){
        return \App\AuthToken::create( [
                'token' => \App\Helper\randomString( 128 ),
                'valid' => \Carbon\Carbon::now()->addYear(),
                'user_id' => $user_id
             ] );
    }

    public static function createQuickToken( $user_id ){
        return \App\AuthToken::create( [
                'token' => \App\Helper\randomString( 128 ),
                'valid' => \Carbon\Carbon::now()->addMinutes( 3 ),
                'user_id' => $user_id
             ] );
    }

    public function prolong(){
        $this->valid = \Carbon\Carbon::now()->addYear();
        $this->save();
    }

    public function cleanup(){
        return $this->forceDelete();
    }

    public function user(){
        return $this->belongsTo( 'App\User' );
    }

    public function isValid(){
    	return( \Carbon\Carbon::createFromTimestamp( strtotime( $this->valid ) ) >= \Carbon\Carbon::now() );
    }
}
