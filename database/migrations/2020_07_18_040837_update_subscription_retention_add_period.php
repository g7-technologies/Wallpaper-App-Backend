<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubscriptionRetentionAddPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_retentions', function (Blueprint $table) {
            $table->tinyInteger( 'period' )->default( 2 );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_retentions', function (Blueprint $table) {
            $table->dropColumn( 'period' );
        });
    }
}
