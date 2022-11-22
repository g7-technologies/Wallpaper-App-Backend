<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppleOriginalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apple_original_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'user_id' )->index();
            $table->string( 'original_transaction_id', 255 )->index();
            $table->timestamps();
        });

        Schema::table('apple_original_transactions', function ($table) {
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apple_original_transactions');
    }
}
