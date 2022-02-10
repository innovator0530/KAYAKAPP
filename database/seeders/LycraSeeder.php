<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LycraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('lycras')->insert([[
            'name' => 'Gorri',
            'color' => '#ff0000',
        ],[
            'name' => 'Hopri',
            'color' => '#ffff00',
        ],[
            'name' => 'Urdin',
            'color' => '#0048ff',
        ],[
            'name' => 'Txuri',
            'color' => '#ffffff',
        ],[
            'name' => 'Beltz',
            'color' => '#000000',
        ],]);
    }
}
