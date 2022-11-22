<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notice;

class AdminIndexController extends Controller
{
	public function index(){
		$day = \Input::get( 'day', date( 'Y-m-d' ) );

		$notices = \App\Notice::whereBetween( 'created_at', [ $day, $day . ' 23:59:59' ] )->orderBy( 'id', 'desc' )->get();

        return \View::make( 'admin.index', compact( 'notices', 'day' ) );
	}

	public function deleteNoticeDo( $id ){
		$notice =  Notice::findOrFail( $id );
		$notice->cleanup();

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Notice successfully deleted';

		return \Redirect::back()->with( compact( '_notice' ) );
	}

	public function importCities(){
		$csv_base = fopen( public_path() . '/worldcities.csv', 'r' );
		$i = 0;
		while( ( $line = fgetcsv( $csv_base ) ) !== false ) {
			$i++;
			if( $i == 1 )
				continue;

			\App\City::create( [ 'name' => $line[ 1 ], 'country' => $line[ 4 ], 'population' => $line[ 9 ] ? $line[ 9 ] : null, 'searchable' => true ] );
		   	//echo( $line[ 1 ] . '<br>' );

		}
		fclose( $csv_base );

		return ( $i - 1 ) . " cities imported";
	}
}
