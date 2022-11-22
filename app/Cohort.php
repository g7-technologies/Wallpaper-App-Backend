<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    protected $table = 'cohorts';

	protected $guarded = ['id', 'created_at', 'updated_at' ];
	protected $fillable = [ 'name' ];

    public static $rules = [
    	'name' => 'required|string|max:255',
    ];

    public function users(){
        return $this->belongsToMany( 'App\User', 'users_2_cohorts' );
    }

    public function currencies(){
        return \DB::table( 'users' )
                 ->select( 'store_country', \DB::raw('count(*) as total') )
                 ->whereIn( 'id', $this->users()->pluck( 'users.id' )->toArray() )
                 ->groupBy( 'store_country' )
                 ->orderBy( 'total', 'DESC' )
                 ->get();
    }

    public function cleanup(){
    	$this->users()->sync( [] );
        return $this->forceDelete();
    }
}
