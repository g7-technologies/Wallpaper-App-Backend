<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStatRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table('stat_records', function (Blueprint $table) {
            $table->unsignedInteger( 'sequence_number' )->nullable();
            $table->string( 'currency', 10 )->nullable();
            $table->float( 'value_potential' )->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stat_records', function (Blueprint $table) {
            $table->dropColumn( 'sequence_number' );
            $table->dropColumn( 'currency' );
            $table->dropColumn( 'value_potential' );
        });
    }
}
