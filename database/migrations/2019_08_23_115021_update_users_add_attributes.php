<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersAddAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->tinyInteger( 'status' )->unsigned()->nullable()->before( 'created_at' );
            $table->tinyInteger( 'role' )->unsigned()->default( \App\User::ROLE_USER )->before( 'created_at' );
            $table->tinyInteger( 'zodiac' )->nullable()->before( 'created_at' );
            $table->date( 'birthdate' )->nullable()->before( 'created_at' );
            $table->string( 'timezone', 60 )->nullable()->before( 'created_at' );
            $table->string( 'push_key', 256 )->nullable()->before( 'created_at' );
            $table->time( 'push_time' )->nullable()->before( 'created_at' );
            $table->string( 'locale', 20 )->nullable()->before( 'created_at' );
            $table->integer( 'version' )->nullable()->before( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn( 'role' );
            $table->dropColumn( 'zodiac' );
            $table->dropColumn( 'birthdate' );
            $table->dropColumn( 'timezone' );
            $table->dropColumn( 'push_key' );
            $table->dropColumn( 'push_time' );
            $table->dropColumn( 'locale' );
            $table->dropColumn( 'version' );
        } );
    }
}
