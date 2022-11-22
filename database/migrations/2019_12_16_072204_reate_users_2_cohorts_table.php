<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReateUsers2CohortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_2_cohorts');
        Schema::create('users_2_cohorts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('cohort_id')->index();
        });

        Schema::table('users_2_cohorts', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cohort_id')->references('id')->on('cohorts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_2_cohorts');
    }
}
