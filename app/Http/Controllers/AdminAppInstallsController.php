<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppInstall;

class AdminAppInstallsController extends Controller
{
    public function index(){
        $app_installs = AppInstall::orderBy( 'id', 'DESC' )->paginate( 50 );

        return \View::make( 'admin.resources.app_installs.index', compact( 'app_installs' ) );
    }

    public function deleteDo( $id ){
        $ai = AppInstall::findOrFail( $id );

        $ai->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

        return \Redirect::route( 'admin.app_installs.index' )->with( compact( '_notice' ) );
    }
}
