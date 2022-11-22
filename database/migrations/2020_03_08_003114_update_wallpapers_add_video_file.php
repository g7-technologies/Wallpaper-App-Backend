<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWallpapersAddVideoFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallpapers', function (Blueprint $table) {
            $table->unsignedBigInteger('video_file_id')->nullable()->index()->after( 'thumb_image_file_id' );
        });

        Schema::table('wallpapers', function ($table) {
            $table->foreign('video_file_id')->references('id')->on('image_files');
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
            $table->dropColumn( 'video_file_id' );
        });
    }
}
