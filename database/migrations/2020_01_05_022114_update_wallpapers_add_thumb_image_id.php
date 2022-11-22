<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWallpapersAddThumbImageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallpapers', function (Blueprint $table) {
            $table->unsignedBigInteger('thumb_image_file_id')->nullable()->index()->after( 'image_file_id' );
        });

        Schema::table('wallpapers', function ($table) {
            $table->foreign('thumb_image_file_id')->references('id')->on('image_files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallpapers', function (Blueprint $table) {
            $table->dropColumn( 'thumb_image_file_id' );
        });
    }
}
