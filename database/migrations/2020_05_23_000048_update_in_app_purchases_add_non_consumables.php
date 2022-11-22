<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInAppPurchasesAddNonConsumables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('in_app_purchases', function (Blueprint $table) {
            $table->dateTime( 'expires_date' )->nullable()->change();
            $table->unsignedBigInteger( 'non_consumable_id' )->nullable()->index();
        });

        Schema::table( 'in_app_purchases', function ($table) {
            $table->foreign( 'non_consumable_id' )->references( 'id' )->on( 'non_consumables' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('in_app_purchases', function (Blueprint $table) {
            $table->dateTime( 'expires_date' )->change();
            $table->dropForeign( [ 'non_consumable_id' ] );
            $table->dropColumn( 'non_consumable_id' );
        });
    }
}
