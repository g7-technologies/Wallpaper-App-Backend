<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStatRecordsAddNonConsumable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stat_records', function (Blueprint $table) {
            $table->unsignedBigInteger( 'non_consumable_id' )->nullable()->index();
        });

        Schema::table( 'stat_records', function ($table) {
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
        Schema::table('stat_records', function (Blueprint $table) {
            $table->dropForeign( [ 'non_consumable_id' ] );
            $table->dropColumn( 'non_consumable_id' );
        });
    }
}
