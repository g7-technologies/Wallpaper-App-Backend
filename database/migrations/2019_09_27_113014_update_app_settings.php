<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAppSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_settings', function ($table) {
            $table->unsignedBigInteger( 'app_version_id' )->nullable()->index();
        });

        Schema::table('app_settings', function ($table) {
            $table->foreign( 'app_version_id' )->references( 'id' )->on( 'app_versions' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_settings', function ($table) {
            $table->dropForeign( 'app_settings_app_version_id_foreign' );
            $table->dropColumn( 'app_version_id' );
        });
    }
}
