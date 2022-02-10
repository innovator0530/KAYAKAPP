<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categories')->insert([[
            'sex_id' => 2,
            'name' => 'Open', 
            'description' => 'Participan mayores de 14 años.',
            'year1' => 1920,
            'year2' => 2007
        ], [
            'sex_id' => 1,
            'name' => 'Open', 
            'description' => 'Participan mayores de 14 años.',
            'year1' => 1920,
            'year2' => 2007
        ], [
            'sex_id' => 2,
            'name' => 'Cadete', 
            'description' => 'Participantes entre 14 y 16 años.',
            'year1' => 2005,
            'year2' => 2007
            
        ], [
            'sex_id' => 1,
            'name' => 'Cadete', 
            'description' => 'Participantes entre 14 y 16 años.',
            'year1' => 2005,
            'year2' => 2007
        ], [
            'sex_id' => 2,
            'name' => 'Junior', 
            'description' => 'Participantes entre 17 y 18 años.',
            'year1' => 2003,
            'year2' => 2004
        ], [
            'sex_id' => 1,
            'name' => 'Junior', 
            'description' => 'Participantes entre 17 y 18 años.',
            'year1' => 2003,
            'year2' => 2004
        ], [
            'sex_id' => 2,
            'name' => 'Senior', 
            'description' => 'Participantes entre 19 y 39 años.',
            'year1' => 1982,
            'year2' => 2002
        ], [
            'sex_id' => 1,
            'name' => 'Senior', 
            'description' => 'Participantes entre 19 y 39 años.',
            'year1' => 1982,
            'year2' => 2002
        ], [
            'sex_id' => 2,
            'name' => 'Master', 
            'description' => 'Participantes entre 40 y 49 años.',
            'year1' => 1972,
            'year2' => 1981
        ], [
            'sex_id' => 1,
            'name' => 'Master', 
            'description' => 'Participantes entre 40 y 49 años.',
            'year1' => 1972,
            'year2' => 1981
        ], [
            'sex_id' => 2,
            'name' => 'GRAND_Master', 
            'description' => 'Participantes entre 50 y 59 años.',
            'year1' => 1962,
            'year2' => 1971
        ], [
            'sex_id' => 1,
            'name' => 'GRAND_Master', 
            'description' => 'Participantes entre 50 y 59 años.',
            'year1' => 1962,
            'year2' => 1971
        ], [
            'sex_id' => 2,
            'name' => 'GRAND_GRAND_Master', 
            'description' => 'Participan mayores de 60 años.',
            'year1' => 1920,
            'year2' => 1961
        ], [
            'sex_id' => 1,
            'name' => 'GRAND_GRAND_Master', 
            'description' => 'Participan mayores de 60 años.',
            'year1' => 1920,
            'year2' => 1961
        ]]);
    }
}
