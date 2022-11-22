<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float( 'value' )->default( 0 );
            $table->boolean( 'is_trial' )->nullable();
            $table->boolean( 'is_reinstall' )->nullable();
            $table->dateTime( 'install_timestamp' );
            $table->unsignedBigInteger('app_install_id')->index();
            $table->unsignedBigInteger('in_app_purchase_id')->index()->nullable();
            $table->unsignedBigInteger('subscription_id')->index()->nullable();
            $table->unsignedBigInteger('search_ads_info_id')->index()->nullable();
            $table->timestamps();
        });

        Schema::table('stat_records', function ($table) {
            $table->foreign('app_install_id')->references('id')->on('app_installs');
            $table->foreign('in_app_purchase_id')->references('id')->on('in_app_purchases');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
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
        Schema::dropIfExists('stat_records');
    }
}
