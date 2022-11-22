<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoroscopesNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscopes_new', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text( 'prediction' );
            $table->tinyInteger( 'zodiac' );
            $table->tinyInteger( 'type' );
            $table->tinyInteger( 'topic' );
            $table->date( 'effective_date' );
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
        Schema::dropIfExists('horoscopes_new');
    }
}
