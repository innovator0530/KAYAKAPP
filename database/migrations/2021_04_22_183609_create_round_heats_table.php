<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoundHeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_heats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('com_cat_mod_participant_id')->constrained('com_cat_mod_participants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('lycra_id')->constrained('lycras');
            $table->integer('round')->unsigned();
            $table->integer('heat')->unsigned();
            $table->float('first_score')->default(0);
            $table->float('second_score')->default(0);
            $table->float('points')->default(0);
            $table->integer('position')->unsigned()->default(0);
            $table->integer('penal')->default(0);
            $table->integer('draw')->default(0);
            $table->integer('status')->default(2);
            $table->integer('ranking')->default(0);
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
        Schema::dropIfExists('round_heats');
    }
}
