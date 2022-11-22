<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'user_id' )->index();
            $table->string( 'receipt_type', 255 );
            $table->string( 'bundle_id', 255 );
            $table->string( 'app_version', 255 );
            $table->longText( 'receipt' );
            $table->timestamps();
        });

        Schema::table('receipts', function ($table) {
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
        Schema::dropIfExists('receipts');
    }
}
