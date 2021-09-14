<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generate_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->bigInteger('status');
            $table->unsignedBigInteger('agent_id')->index();
            $table->unsignedBigInteger('client_id')->index();
            $table->bigInteger('generate_type');
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
        Schema::dropIfExists('generate_codes');
    }
}
