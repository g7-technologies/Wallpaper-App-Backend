<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubscriptionRevenues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_revenues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscription_id')->index();
            $table->string( 'currency', 10 );
            $table->date( 'start_date' );
            $table->date( 'stop_date' )->nullable();
            $table->float( 'revenue', 8, 2 );
            $table->timestamps();
        });

        Schema::table('subscription_revenues', function ($table) {
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->float( 'default_revenue', 8, 2 )->default( 0 )->before( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_revenues');
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn( 'default_revenue' );
        });
    }
}
