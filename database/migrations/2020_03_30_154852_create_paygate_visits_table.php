<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaygateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paygate_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string( 'random_string', 255 )->unique();
            $table->dateTime( 'last_ping_time' );
            $table->boolean( 'processed' )->default( false );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paygate_visits');
    }
}
