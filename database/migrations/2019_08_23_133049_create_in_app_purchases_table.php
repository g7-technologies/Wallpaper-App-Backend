<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInAppPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_app_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'receipt_id' )->nullable()->index();
            $table->unsignedBigInteger( 'subscription_id' )->nullable()->index();
            $table->bigInteger( 'transaction_id' );
            $table->bigInteger( 'original_transaction_id' );
            $table->dateTime( 'purchase_date' );
            $table->dateTime( 'expires_date' );
            $table->boolean( 'is_trial_period' );
            $table->boolean( 'valid' )->nullable();
            $table->timestamps();
        });

        Schema::table( 'in_app_purchases', function ($table) {
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
        Schema::dropIfExists('in_app_purchases');
    }
}
