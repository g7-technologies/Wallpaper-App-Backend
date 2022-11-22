<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'user_id' )->index();
            $table->dateTime( 'valid_till' );
            $table->timestamps();
        });

        Schema::table('free_access', function ($table) {
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_access');
    }
}
