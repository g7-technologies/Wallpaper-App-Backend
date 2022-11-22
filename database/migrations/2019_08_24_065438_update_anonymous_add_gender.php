<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAnonymousAddGender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anonymous', function ($table) {
            $table->tinyInteger( 'gender' )->unsigned()->before( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anonymous', function ($table) {
            $table->dropColumn( 'gender' );
        });
    }
}
