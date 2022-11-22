<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallpapers2CategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpapers_2_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallpaper_id')->index();
            $table->unsignedBigInteger('category_id')->index();
        });

        Schema::table('wallpapers_2_categories', function ($table) {
            $table->foreign('wallpaper_id')->references('id')->on('wallpapers');
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::table('wallpapers', function ($table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallpapers_2_categories');
        Schema::table('wallpapers', function ($table) {
            $table->unsignedBigInteger( 'category_id' )->nullable()->index();
        });
        Schema::table( 'wallpapers', function ($table) {
            $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' );
        } );
    }
}
