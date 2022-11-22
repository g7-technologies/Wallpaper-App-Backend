<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryQuote extends Model
{
    protected $table = 'category_quotes';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'quote', 'category_id' ];

    public static $rules = [
    	'quote' => 'required|string',
        'category_id' => 'required|numeric|min:0',
    ];

    public function category(){
        return $this->belongsTo( 'App\Category' );
    }

    public function cleanup(){
        return $this->forceDelete();
    }
}
