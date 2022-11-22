<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppInstallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('app_installs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string( 'idfa', 255 )->nullable();
            $table->integer( 'version' )->unsigned();
            $table->string( 'random_string', 255 )->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string( 'random_string', 255 )->nullable()->before( 'created_at' );
        });

        Schema::table('anonymous', function (Blueprint $table) {
            $table->string( 'random_string', 255 )->nullable()->before( 'created_at' );
        });        

        Schema::table('search_ads_info', function (Blueprint $table) {
            $table->dropForeign( 'search_ads_info_user_id_foreign' );
            $table->dropColumn( 'user_id' );
        });

        Schema::dropIfExists('users_2_search_ads_info');
        Schema::create('users_2_search_ads_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('search_ads_info_id')->index();
        });

        Schema::table('users_2_search_ads_info', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('search_ads_info_id')->references('id')->on('search_ads_info');
        });

        Schema::dropIfExists('app_installs_2_search_ads_info');
        Schema::create('app_installs_2_search_ads_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('app_install_id')->index();
            $table->unsignedBigInteger('search_ads_info_id')->index();
        });

        Schema::table('app_installs_2_search_ads_info', function ($table) {
            $table->foreign('app_install_id')->references('id')->on('app_installs');
            $table->foreign('search_ads_info_id')->references('id')->on('search_ads_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_installs_2_search_ads_info');
        Schema::dropIfExists('users_2_search_ads_info');
        Schema::dropIfExists('app_installs');

        Schema::table('search_ads_info', function (Blueprint $table) {
            $table->unsignedBigInteger( 'user_id' )->index()->nullable()->after( 'id' );
        });

        Schema::table('search_ads_info', function ($table) {
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        });

        Schema::table('anonymous', function (Blueprint $table) {
            $table->dropColumn( 'random_string' );
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn( 'random_string' );
        });
    }
}
