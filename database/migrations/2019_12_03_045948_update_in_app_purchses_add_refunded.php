<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInAppPurchsesAddRefunded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('in_app_purchases', function (Blueprint $table) {
            $table->boolean( 'refunded' )->default( false )->before( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_purchses_add_refunded', function (Blueprint $table) {
            $table->dropColumn( 'refunded' );
        });
    }
}
