<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaygateVisitsAddProccessedSpecial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paygate_visits', function (Blueprint $table) {
            $table->boolean( 'processed_special' )->default( true );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paygate_visits', function (Blueprint $table) {
            $table->dropColumn( 'processed_special' );
        });
    }
}
