<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersAddMarketingData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string( 'idfa', 255 )->nullable()->before( 'created_at' );
            $table->boolean( 'ad_tracking', 255 )->nullable()->before( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn( 'idfa' );
            $table->dropColumn( 'ad_tracking' );
        });
    }
}
