<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\AuthToken;
use App\Notice;

class AdminUsersController extends Controller
{
    public $search_flag, $search_count, $search;

    public function __construct(){
        $this->search_flag = false;
        $this->search_count = 0;
        $this->search = [];
    }

    public function applySearch( $users ){
        $this->search[ 'name' ] = trim( \Input::get( 'search_name', null ) );
        $this->search[ 'payments_count' ] = \Input::get( 'search_payments_count', null );
        $this->search[ 'payments_count' ] = $this->search[ 'payments_count' ] > 0 ? $this->search[ 'payments_count' ] : null;

        $user_ids = \DB::table('users')
                    ->select( [ 'users.id' ] );

        if( $term = $this->search[ 'name' ] ){
            $user_ids = $user_ids->where( function( $query ) use ( $term ) {
                $query->where( 'users.name', 'LIKE', '%' . $term . '%' );//->orWhere( 'users.email', 'LIKE', '%' . $term . '%' );
            } );
            $this->search_flag = true;
        }

        if( $payment_count = $this->search[ 'payments_count' ] ){
            $revelvant_receipts_ids = \DB::select( 'select count(*), receipt_id from in_app_purchases where is_trial_period <> 1 group by receipt_id having count(*) >= ?', [ $payment_count ] );
            $filter_ids = [];
            foreach( $revelvant_receipts_ids as $rec ) {
                $filter_ids[] = $rec->receipt_id;
            }

            $filter_ids_size = sizeof( $filter_ids );
            if( $filter_ids_size ){
                $sql = 'select user_id from receipts where id in ( ';
                $i = 1;
                foreach( $filter_ids as $f_id ){
                    if( $i < $filter_ids_size )
                        $sql = $sql . $f_id . ', ';
                    else
                        $sql = $sql . $f_id;
                    $i++;
                }
                $sql = $sql . ')';
                $filter_ids = [];
                $relevant_user_ids = \DB::select( $sql );
                foreach( $relevant_user_ids as $rec ) {
                    $filter_ids[] = $rec->user_id;
                }
            }

            if( sizeof( $filter_ids ) )
                $user_ids = $user_ids->whereIn( 'id', $filter_ids );
            else
                $user_ids = null;

            $this->search_flag = true;
        }

        if( $user_ids )
            $ids = array_unique( $user_ids->pluck( 'id' )->all() );
        else
            $ids = [];

        if( $payment_count ){
            $tester_ids = \App\User::where( 'tester', true )->pluck( 'id' )->all();
            $ids = array_diff( $ids, $tester_ids );
        }

        $users = $users->whereIn( 'id', $ids )->orderBy( 'id', 'desc' );
        $this->search_count = sizeof( $ids );

        return $users;
    }

    public function index(){
        $users = User::where( 'email', '<>', '@' );
        $total_count = $users->count();
        $users = $this->applySearch( $users )->paginate( 20 );

        $title = 'Users';
        $search_flag = $this->search_flag;
        $search_count = $this->search_count;
        $search = $this->search;

        return \View::make( 'admin.resources.users.index', compact( 'users', 'title', 'search_count', 'search_flag', 'search', 'total_count' ) );
    }

    public function show( $id ){
    	$user = User::findOrFail( $id );

    	return \View::make( 'admin.resources.users.show', compact( 'user' ) );
    }

    public function edit( $id ){
    	if( !$id )
    		$user = new User;
    	else
    		$user = User::findOrFail( $id );

    	return \View::make( 'admin.resources.users.edit', compact( 'user' ) );
    }

    public function editDo( $id, Request $request ){
    	if( !$id )
    		$user = NULL;
    	else
    		$user = User::findOrFail( $id );

    	$rules = User::$admin_edit_rules;
    	if( $user  ){	
            if( !\Input::get( 'password' ) ){
                unset( $rules[ 'password' ] );
                \Input::replace( \Input::except( 'password' ) );
            }

    		if( $user->email == \Input::get( 'email', NULL ) )
    			unset( $rules[ 'email' ] );
    	}

		$this->validate( $request, $rules );

		if( $user ){
			$user->fill( \Input::all() );
			$user->save();
			$user->init();
		}
		else{
			$user = User::create( \Input::all() );
            $user->init();
		}

        if( $pwd = \Input::get( 'password' ) ){
            $user->password = bcrypt( $pwd );
            $user->save();
        }

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully ' . ( $id ? 'edited' : 'added' );

    	return \Redirect::to( route( 'admin.users.show', $user->id ) )->with( compact( '_notice' ) );
    }

    public function deleteDo( $id ){
    	$u = User::findOrFail( $id );

        $u->cleanup();

		$_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

		return \Redirect::route( 'admin.users.index' )->with( compact( '_notice' ) );
    }

    public function deleteTokenDo( $id ){
        $at = AuthToken::findOrFail( $id );

        $at->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Record successfully deleted';

        return \Redirect::back()->with( compact( '_notice' ) );
    }

    public function testerMark( $id ){
        $u = User::findOrFail( $id );

        $u->tester = true;
        $u->save();

        \App\Helper\reportAmplitudeEvent(
            'Tester Mark Event', $u->id, [], [ 'tester' => true ] );

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Marked as tester';

        return \Redirect::back()->with( compact( '_notice' ) );
    }

    public function purge(){
        if( \App::environment( [ 'production' ] ) ){
            $_notice[ 'type' ] = 'danger';
            $_notice[ 'message' ] = 'Not on the production!';

            return \Redirect::back()->with( compact( '_notice' ) );
        }

        foreach( \App\User::where( 'role', '<>', \App\User::ROLE_ADMIN )->get() as $u )
            $u->cleanup();

        $_notice[ 'type' ] = 'success';
        $_notice[ 'message' ] = 'Purged all user records';

        return \Redirect::route( 'admin.users.index' )->with( compact( '_notice' ) );
    }
}
