<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnonymousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anonymous', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string( 'name', 256 )->nullable();
            $table->tinyInteger( 'status' )->unsigned()->nullable()->before( 'created_at' );
            $table->tinyInteger( 'zodiac' );
            $table->date( 'birthdate' );
            $table->string( 'timezone', 60 )->nullable();
            $table->string( 'push_key', 256 );
            $table->string( 'locale', 20 )->nullable()->before( 'created_at' );
            $table->integer( 'version' )->nullable()->before( 'created_at' );
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
        Schema::dropIfExists('anonymous');
    }
}
