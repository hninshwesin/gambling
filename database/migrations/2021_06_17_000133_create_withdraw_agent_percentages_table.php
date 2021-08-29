<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawAgentPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_agent_percentages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('withdraw_id')->index();
            $table->foreign('withdraw_id')->references('id')->on('withdraws')->onDelete('cascade');
            $table->bigInteger('total_percent');
            $table->unsignedBigInteger('admin_id')->index();
            $table->bigInteger('admin');
            $table->unsignedBigInteger('agent_id')->index();
            $table->bigInteger('agent');
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
        Schema::dropIfExists('withdraw_agent_percentages');
    }
}
