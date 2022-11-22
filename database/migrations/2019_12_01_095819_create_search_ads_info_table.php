<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchAdsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('search_ads_info');
        Schema::create('search_ads_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'user_id' )->index()->nullable();
            $table->timestamp('clicked_ad_at')->nullable();
            $table->timestamp('downloaded_app_at')->nullable();
            $table->string('company_name',255)->nullable();
            $table->integer('campaign_id');
            $table->string('campaign_name',255);
            $table->integer('ad_group_id')->nullable();
            $table->string('ad_group_name',255)->nullable();
            $table->string('keyword',255)->nullable();
            $table->timestamps();
        });

        Schema::table('search_ads_info', function ($table) {
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_ads_info');
    }
}
