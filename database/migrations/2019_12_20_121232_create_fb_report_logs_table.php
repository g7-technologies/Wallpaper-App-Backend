<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbReportLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fb_report_logs');
        Schema::create('fb_report_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('in_app_purchase_id')->index();
            $table->timestamps();
        });

        Schema::table('fb_report_logs', function ($table) {
            $table->foreign('in_app_purchase_id')->references('id')->on('in_app_purchases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fb_report_logs');
    }
}
