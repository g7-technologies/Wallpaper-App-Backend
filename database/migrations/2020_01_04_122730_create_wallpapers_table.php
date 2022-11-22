<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallpapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->bigIncrements( 'id' );
            $table->string( 'name', 255 );
            $table->boolean( 'paid' )->default( true );
            $table->unsignedBigInteger( 'image_file_id' )->nullable()->index();
            $table->unsignedBigInteger( 'category_id' )->nullable()->index();
            $table->integer( 'sort' )->nullable()->unsigned();
            $table->string( 'hash', 255 )->nullable();
            $table->timestamps();
        });

        Schema::table( 'wallpapers', function ($table) {
            $table->foreign( 'image_file_id' )->references( 'id' )->on( 'image_files' );
            $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallpapers');
    }
}
