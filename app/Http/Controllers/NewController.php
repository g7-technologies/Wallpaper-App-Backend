<?php

namespace App\Http\Controllers;

use ReceiptValidator\iTunes\Validator as iTunesValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\AppInstall;
use App\Category;
use App\Wallpaper;
use DB;

class NewController extends Controller
{
	public function NewAppRegister( Request $request )
	{
		$rules = [
            'idfa' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            return redirect()->back()->with('error_msg', $validator->errors()->first());
        }

		$app_installs = AppInstall::create([
            'idfa' => $request->idfa,
            'version' => 1,
            'random_string' => $this->randomString(128)
        ]);
	}

	public function GetCategories()
	{
		$categories = Category::with(['wallpapers'])->where( 'deleted', false )->orderBy( 'sort', 'ASC' )->get();
		return $categories;
	}

	function randomString( $length ){
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen( $characters );
	    $randomString = '';
	    for( $i = 0; $i < $length; $i++ ){	$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];	}
	    return $randomString;
	}
}