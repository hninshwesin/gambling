<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampAndPriceToRawGoldAPISTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_gold_a_p_i_s', function (Blueprint $table) {
            $table->bigInteger('timestamp')->nullable();
            $table->double('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_gold_a_p_i_s', function (Blueprint $table) {
            //
        });
    }
}
