<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrepareToAmplitudeReporting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'in_app_purchases', function (Blueprint $table) {
            $table->boolean( 'analyzed' )->default( false )->nullable()->before( 'created_at' );
            $table->boolean( 'auto_renew_status_on_purchase' )->default( false )->nullable()->before( 'created_at' );
        });
        Schema::table( 'users', function (Blueprint $table) {
            $table->boolean( 'is_in_billing_retry_period' )->default( false )->nullable()->before( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'in_app_purchases', function ( Blueprint $table ){
            $table->dropColumn( 'analyzed' );
            $table->dropColumn( 'auto_renew_status_on_purchase' );
        } );

        Schema::table( 'users', function ( Blueprint $table ){
            $table->dropColumn( 'is_in_billing_retry_period' );
        } );
    }
}
