<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenewalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewal_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'receipt_id' )->nullable()->index();
            $table->unsignedBigInteger( 'subscription_id' )->nullable()->index();
            $table->bigInteger( 'original_transaction_id' );
            $table->boolean( 'auto_renew_status' )->nullable();
            $table->integer( 'expiration_intent' )->nullable();
            $table->boolean( 'is_in_billing_retry_period' )->nullable();
            $table->timestamps();
        });

        Schema::table( 'renewal_info', function ($table) {
            $table->foreign( 'receipt_id' )->references( 'id' )->on( 'receipts' );
            $table->foreign( 'subscription_id' )->references( 'id' )->on( 'subscriptions' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewal_info');
    }
}
