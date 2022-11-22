<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('horoscopes_new');
        Schema::dropIfExists('anonymous_2_app_installs');
        Schema::dropIfExists('horoscopes');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('anonymous');
        Schema::dropIfExists('compatibility');
        Schema::table('users', function (Blueprint $table) {
            /*
            $table->dropColumn( 'name' );
            $table->dropColumn( 'horo_push_sent_time' );
            $table->dropColumn( 'push_notifications' );
            $table->dropColumn( 'city_id' );
            $table->dropColumn( 'gender' );
            $table->dropColumn( 'push_time' );
            $table->dropColumn( 'push_key' );
            $table->dropColumn( 'birthdate' );
            $table->dropColumn( 'zodiac' );
            $table->dropColumn( 'status' );
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
