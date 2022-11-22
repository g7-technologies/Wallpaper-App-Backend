<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDailyRecordsAddCurrencyAndTrials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_records', function (Blueprint $table) {
            $table->unsignedInteger( 'trials' )->default( 0 );
            $table->string( 'currency', 10 )->nullable();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropColumn( 'currency', 10 );
            $table->dropColumn( 'trials' );
        } );
    }
}
