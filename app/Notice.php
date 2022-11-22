<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
	protected $table = 'notices';

	protected $fillable = [
		'user_id',
		'notice',
		'level',
		'type' ];

	protected $guarded = [];

	public static $rules = [
		'user_id' => 'required',
		'notice' => 'required',
	];

	const LEVEL_NONE = null;
	const LEVEL_DEBUG = 1;
	const LEVEL_INFO = 2;
	const LEVEL_WARNING = 3;
	const LEVEL_ERROR = 4;

	public static $levels = [
		self::LEVEL_NONE => 'not set',
		self::LEVEL_DEBUG => 'debug',
		self::LEVEL_INFO => 'information',
		self::LEVEL_WARNING => 'warnings',
		self::LEVEL_ERROR => 'errors',
	];

	const TYPE_NONE = null;
	const TYPE_USER = 1;
	const TYPE_MATCHING = 2;
	const TYPE_CHAT = 3;
	const TYPE_PAYMENT = 4;

	public static $types = [
		self::TYPE_NONE => 'test',
		self::TYPE_USER => 'user',
		self::TYPE_MATCHING => 'matching',
		self::TYPE_CHAT => 'chats',
		self::TYPE_PAYMENT => 'payments',
	];

	public static function alert( $user_id, $notice, $level = self::LEVEL_NONE, $type = self::TYPE_NONE ){
        return self::create( [
            'user_id' => $user_id,
            'notice' => $notice,
            'level' => $level,
            'type' => $type
        ] );
	}

    public function user(){
        return $this->belongsTo( 'App\User' );
    }

	public function cleanup(){
		return $this->forceDelete();
	}
}
