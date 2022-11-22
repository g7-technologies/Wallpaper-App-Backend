<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnonymousRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'anonymous', function ( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->string( 'notification_key', 255 )->nullable();
            $table->string( 'timezone', 60 )->nullable();
            $table->string( 'locale', 20 )->nullable();
            $table->string( 'random_string', 255 )->nullable();
            $table->integer( 'version' )->nullable();
            $table->timestamps();
        } );
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
