<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger( 'category_id' )->nullable()->index();
            $table->text( 'quote' );
            $table->timestamps();
        });

        Schema::table( 'category_quotes', function ($table) {
            $table->foreign( 'category_id' )->references( 'id' )->on( 'categories' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_quotes');
    }
}
