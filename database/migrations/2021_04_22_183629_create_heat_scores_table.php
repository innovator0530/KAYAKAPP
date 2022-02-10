<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeatScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heat_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_heat_id')->constrained('round_heats')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users');
            $table->float('wave_1', 8, 1)->default(0);
            $table->float('wave_2', 8, 1)->default(0);
            $table->float('wave_3', 8, 1)->default(0);
            $table->float('wave_4', 8, 1)->default(0);
            $table->float('wave_5', 8, 1)->default(0);
            $table->float('wave_6', 8, 1)->default(0);
            $table->float('wave_7', 8, 1)->default(0);
            $table->float('wave_8', 8, 1)->default(0);
            $table->float('wave_9', 8, 1)->default(0);
            $table->float('wave_10', 8, 1)->default(0);
            $table->integer('penal')->default(0);
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
        Schema::dropIfExists('heat_scores');
    }
}
