<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnonymous2AppInstallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('anonymous_2_app_installs');
        Schema::create('anonymous_2_app_installs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('app_install_id')->index();
        });

        Schema::table('anonymous_2_app_installs', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('app_install_id')->references('id')->on('app_installs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anonymous_2_app_installs');
    }
}
