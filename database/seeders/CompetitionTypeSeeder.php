<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('competition_types')->insert([[
            'name' => 'Cto. Euskadi',
            'description' => 'Si el Ranking de la Copa puntúa con más puntos'
        ], [
            'name' => 'Cto.Bizkaia and Cto. Guipúzcoa',
            'description' => 'no anota una copa'
        ], [
            'name' => 'Cup',
            'description' => 'Si logras el Ranking de la Copa'
        ]]);
    }
}
