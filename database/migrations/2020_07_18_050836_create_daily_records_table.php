<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date( 'day' );
            $table->bigInteger( 'installs' )->unsigned();
            $table->float( 'value' )->nullable();
            $table->string( 'channel', 255 )->nullable();
            $table->string( 'campaign', 255 )->nullable();
            $table->string( 'adgroup', 255 )->nullable();
            $table->string( 'keyword', 255 )->nullable();
            $table->timestamps();
        });

        Schema::create('daily_record_expectations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'daily_record_id' );
            $table->tinyInteger( 'period' );
            $table->float( 'value' )->nullable();
            $table->timestamps();
        });

        Schema::table( 'daily_record_expectations', function ($table) {
            $table->foreign( 'daily_record_id' )->references( 'id' )->on( 'daily_records' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_records');
    }
}
