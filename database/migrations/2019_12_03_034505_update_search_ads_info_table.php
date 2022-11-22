<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSearchAdsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('search_ads_info', function (Blueprint $table) {
            $table->dropColumn('clicked_ad_at');
            $table->dropColumn('downloaded_app_at');
            $table->dropColumn('company_name');
            $table->dropColumn('campaign_id');
            $table->dropColumn('campaign_name');
            $table->dropColumn('ad_group_id');
            $table->dropColumn('ad_group_name');
            $table->dropColumn('keyword');

            $table->timestamp( 'iad-conversion-date' )->nullable()->before( 'created_at' );
            $table->string( 'iad-keyword', 255 )->nullable()->before( 'created_at' );
            $table->string( 'iad-keyword-id', 255 )->nullable()->before( 'created_at' );
            $table->string( 'iad-country-or-region', 255 )->nullable()->before( 'created_at' );
            $table->unsignedBigInteger( 'iad-creativeset-id' )->nullable()->before( 'created_at' );
            $table->string( 'iad-conversion-type', 255 )->nullable()->before( 'created_at' );
            $table->timestamp( 'iad-click-date' )->nullable()->before( 'created_at' );
            $table->string( 'iad-adgroup-name', 255 )->nullable()->before( 'created_at' );
            $table->unsignedBigInteger( 'iad-campaign-id' )->nullable()->before( 'created_at' );
            $table->string( 'iad-org-name', 255 )->nullable()->before( 'created_at' );
            $table->unsignedBigInteger( 'iad-lineitem-id' )->nullable()->before( 'created_at' );
            $table->string( 'iad-keyword-matchtype', 255 )->nullable()->before( 'created_at' );
            $table->unsignedBigInteger( 'iad-org-id' )->nullable()->before( 'created_at' );
            $table->string( 'iad-lineitem-name', 255 )->nullable()->before( 'created_at' );
            $table->boolean( 'iad-attribution' )->nullable()->before( 'created_at' );
            $table->timestamp( 'iad-purchase-date' )->nullable()->before( 'created_at' );
            $table->string( 'iad-campaign-name', 255 )->nullable()->before( 'created_at' );
            $table->unsignedBigInteger( 'iad-adgroup-id' )->nullable()->before( 'created_at' );
            $table->string( 'iad-creativeset-name', 255 )->nullable()->before( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('search_ads_info', function (Blueprint $table) {
            $table->timestamp('clicked_ad_at')->nullable()->before( 'created_at' );
            $table->timestamp('downloaded_app_at')->nullable()->before( 'created_at' );
            $table->string('company_name',255)->nullable()->before( 'created_at' );
            $table->integer('campaign_id')->before( 'created_at' );
            $table->string('campaign_name',255)->before( 'created_at' );
            $table->integer('ad_group_id')->nullable()->before( 'created_at' );
            $table->string('ad_group_name',255)->nullable()->before( 'created_at' );
            $table->string('keyword',255)->nullable()->before( 'created_at' );

            $table->dropColumn( 'iad-conversion-date' );
            $table->dropColumn( 'iad-keyword' );
            $table->dropColumn( 'iad-keyword-id' );
            $table->dropColumn( 'iad-country-or-region' );
            $table->dropColumn( 'iad-creativeset-id' );
            $table->dropColumn( 'iad-conversion-type' );
            $table->dropColumn( 'iad-click-date' );
            $table->dropColumn( 'iad-adgroup-name' );
            $table->dropColumn( 'iad-campaign-id' );
            $table->dropColumn( 'iad-org-name' );
            $table->dropColumn( 'iad-lineitem-id' );
            $table->dropColumn( 'iad-keyword-matchtype' );
            $table->dropColumn( 'iad-org-id' );
            $table->dropColumn( 'iad-lineitem-name' );
            $table->dropColumn( 'iad-attribution' );
            $table->dropColumn( 'iad-purchase-date' );
            $table->dropColumn( 'iad-campaign-name' );
            $table->dropColumn( 'iad-adgroup-id' );
            $table->dropColumn( 'iad-creativeset-name' );
        });
    }
}
