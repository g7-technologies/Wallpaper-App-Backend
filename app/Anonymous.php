<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anonymous extends Model
{
	protected $table = 'anonymous';

    protected $fillable = [
        'notification_key', 'timezone', 'locale', 'random_string', 'version'
    ];

    public static $api_edit_rules = [
        'timezone' => 'string|max:60',
        'locale' => 'string|max:20|required',
        'version' => 'integer',
        'random_string' => 'string|max:255',
        'notification_key' => 'string|max:255|required',
    ];

    public static $admin_edit_rules = [
        'timezone' => 'string|max:60',
        'locale' => 'string|max:20',
        'version' => 'integer',
        'random_string' => 'nullable|string|max:255',
        'notification_key' => 'nullable|string|max:255',
    ];

    public function cleanup(){
        return $this->forceDelete();
    }
}
