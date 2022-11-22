<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallpapers2TagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('wallpapers_2_tags');
        Schema::create('wallpapers_2_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('wallpaper_id')->index();
            $table->unsignedBigInteger('tag_id')->index();
        });

        Schema::table('wallpapers_2_tags', function ($table) {
            $table->foreign('wallpaper_id')->references('id')->on('wallpapers');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallpapers_2_tags');
    }
}
