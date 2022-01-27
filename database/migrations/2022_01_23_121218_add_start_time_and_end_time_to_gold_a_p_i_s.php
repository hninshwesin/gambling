<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartTimeAndEndTimeToGoldAPIS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gold_a_p_i_s', function (Blueprint $table) {
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gold_a_p_i_s', function (Blueprint $table) {
            //
        });
    }
}
