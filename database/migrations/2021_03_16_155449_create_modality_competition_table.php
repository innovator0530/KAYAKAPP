<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalityCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modality_competition', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modality_id')->constrained('modalities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('competition_id')->constrained('competitions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('modality_competitions');
    }
}
