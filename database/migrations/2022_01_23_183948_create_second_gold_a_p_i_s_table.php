<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondGoldAPISTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('second_gold_a_p_i_s', function (Blueprint $table) {
            $table->id();
            $table->double('open_price')->nullable();
            $table->double('high_price')->nullable();
            $table->double('low_price')->nullable();
            $table->double('close_price')->nullable();
            $table->bigInteger('timestamp');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('second_gold_a_p_i_s');
    }
}
