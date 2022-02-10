<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('rankings')->insert([[
            'name' => 'Ranking Cto Euskadi',
            'year' => '2021'
        ], [
            'name' => 'Ranking Other Compets',
            'year' => '2021'
        ]]);
    }
}
