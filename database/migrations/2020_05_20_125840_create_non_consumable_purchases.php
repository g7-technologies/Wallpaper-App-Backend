<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNonConsumablePurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'non_consumables', function ( Blueprint $table ) {
            $table->bigIncrements( 'id' );
            $table->string( 'name', 255 )->default( null );
            $table->string( 'product_id', 255 )->default( null );
            $table->float( 'default_revenue', 8, 2 )->default( 0 )->before( 'created_at' );
            $table->boolean( 'lifetime' )->default( true );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('non_consumables');
    }
}
