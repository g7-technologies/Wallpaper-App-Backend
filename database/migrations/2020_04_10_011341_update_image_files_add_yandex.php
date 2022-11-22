<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateImageFilesAddYandex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_files', function (Blueprint $table) {
            $table->text( 'cloud_disk_path' )->nullable()->after( 'original_name' );
            $table->text( 'cloud_public_url' )->nullable()->after( 'original_name' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_files', function (Blueprint $table) {
            $table->dropColumn( 'cloud_disk_path' );
            $table->dropColumn( 'cloud_public_url' );
        });
    }
}
