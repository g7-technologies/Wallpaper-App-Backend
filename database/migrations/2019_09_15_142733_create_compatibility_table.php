<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompatibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::dropIfExists('compatibility');
        Schema::create('compatibility', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger( 'zodiac_one' );
            $table->tinyInteger( 'zodiac_two' );
            $table->text( 'compatibility' );
            $table->float( 'general_score' );
            $table->tinyInteger( 'love_score' );
            $table->tinyInteger( 'trust_score' );
            $table->tinyInteger( 'emotions_score' );
            $table->tinyInteger( 'values_score' );
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
        Schema::dropIfExists('compatibility');
    }
}
