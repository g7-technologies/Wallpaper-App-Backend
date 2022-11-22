<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchAdsInfo extends Model
{
    protected $table = 'search_ads_info';

    protected $fillable = [
        'iad-conversion-date',
        'iad-keyword',
        'iad-keyword-id',
        'iad-country-or-region',
        'iad-creativeset-id',
        'iad-conversion-type',
        'iad-click-date',
        'iad-adgroup-name',
        'iad-campaign-id',
        'iad-org-name',
        'iad-lineitem-id',
        'iad-keyword-matchtype',
        'iad-org-id',
        'iad-lineitem-name',
        'iad-attribution',
        'iad-purchase-date',
        'iad-campaign-name',
        'iad-adgroup-id',
        'iad-creativeset-name'
    ];

    public static $rules = [
        'iad-conversion-date' => 'nullable|date_format:Y-m-d H:i:s',
        'iad-keyword' => 'nullable|string|max:255',
        'iad-keyword-id' => 'nullable|string|max:255',
        'iad-country-or-region' => 'nullable|string|max:255',
        'iad-creativeset-id' => 'nullable|numeric',
        'iad-conversion-type' => 'nullable|string|max:255',
        'iad-click-date' => 'nullable|date_format:Y-m-d H:i:s',
        'iad-adgroup-name' => 'nullable|string|max:255',
        'iad-campaign-id' => 'nullable|numeric',
        'iad-org-name' => 'nullable|string|max:255',
        'iad-lineitem-id' => 'nullable|numeric',
        'iad-keyword-matchtype' => 'nullable|string|max:255',
        'iad-org-id' => 'nullable|numeric',
        'iad-lineitem-name' => 'nullable|string|max:255',
        'iad-attribution' => 'nullable|boolean',
        'iad-purchase-date' => 'nullable|date_format:Y-m-d H:i:s',
        'iad-campaign-name' => 'nullable|string|max:255',
        'iad-adgroup-id' => 'nullable|numeric',
        'iad-creativeset-name' => 'nullable|string|max:255',
    ];

    public static $timefields = [ 'iad-conversion-date', 'iad-click-date', 'iad-purchase-date' ];

    public function users(){
        return $this->belongsToMany( 'App\User', 'users_2_search_ads_info' );
    }

    public function appInstalls(){
        return $this->belongsToMany( 'App\AppInstall', 'app_installs_2_search_ads_info' );
    }

    public function statRecords(){
        return $this->hasMany( 'App\StatRecord' );
    }

    public function channelNameplate(){
        return $this->getAttribute( 'iad-campaign-id' ) . ' ' . $this->getAttribute( 'iad-campaign-name' );
    }

    public function adgroupNameplate(){
        return $this->getAttribute( 'iad-adgroup-name' );
    }

    public function keywordNameplate(){
        return $this->getAttribute( 'iad-keyword-id' ) ? $this->getAttribute( 'iad-keyword' ) : 'Search Match';
    }

    public function cleanup(){
        foreach( $this->statRecords as $sr )
            $sr->cleanup();

        $this->appInstalls()->sync( [] );
        $this->users()->sync( [] );
        
        return $this->forceDelete();
    }
}
