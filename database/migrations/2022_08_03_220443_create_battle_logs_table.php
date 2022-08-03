<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battle_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('army1_id')->references('id')->on('armies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('army2_id')->references('id')->on('armies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('winner')->references('id')->on('armies')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('battle_log');
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
        Schema::dropIfExists('battle_logs');
    }
}
