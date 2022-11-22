<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCategoriesAddDeleted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('deleted')->default( false );
        });

        Schema::table('wallpapers', function (Blueprint $table) {
            $table->boolean('deleted')->default( false );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('deleted');
        });

        Schema::table('wallpapers', function (Blueprint $table) {
            $table->dropColumn('deleted');
        });
    }
}
