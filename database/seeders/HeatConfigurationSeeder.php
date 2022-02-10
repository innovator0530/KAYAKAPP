<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeatConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('heat_configurations')->insert([[
            'participant_number' => 1,
            'assign_array' => json_encode([1]),
        ],[
            'participant_number' => 2,
            'assign_array' => json_encode([2]),
        ],[
            'participant_number' => 3,
            'assign_array' => json_encode([3]),
        ],[
            'participant_number' => 4,
            'assign_array' => json_encode([4]),
        ],[
            'participant_number' => 5,
            'assign_array' => json_encode([5]),
        ],[
            'participant_number' => 6,
            'assign_array' => json_encode([3,3]),
        ],[
            'participant_number' => 7,
            'assign_array' => json_encode([4,3]),
        ],[
            'participant_number' => 8,
            'assign_array' => json_encode([4,4]),
        ],[
            'participant_number' => 9,
            'assign_array' => json_encode([3,3,3]),
        ],[
            'participant_number' => 10,
            'assign_array' => json_encode([4,3,3]),
        ],[
            'participant_number' => 11,
            'assign_array' => json_encode([4,4,3]),
        ],[
            'participant_number' => 12,
            'assign_array' => json_encode([4,4,4]),
        ],[
            'participant_number' => 13,
            'assign_array' => json_encode([4,3,3,3]),
        ],[
            'participant_number' => 14,
            'assign_array' => json_encode([4,4,3,3]),
        ],[
            'participant_number' => 15,
            'assign_array' => json_encode([4,4,4,3]),
        ],[
            'participant_number' => 16,
            'assign_array' => json_encode([4,4,4,4]),
        ],[
            'participant_number' => 17,
            'assign_array' => json_encode([5,4,4,4]),
        ],[
            'participant_number' => 18,
            'assign_array' => json_encode([3,3,3,3,3,3]),
        ],[
            'participant_number' => 19,
            'assign_array' => json_encode([4,3,3,3,3,3]),
        ],[
            'participant_number' => 20,
            'assign_array' => json_encode([4,4,3,3,3,3]),
        ],[
            'participant_number' => 21,
            'assign_array' => json_encode([4,4,4,3,3,3]),
        ],[
            'participant_number' => 22,
            'assign_array' => json_encode([4,4,4,4,3,3]),
        ],[
            'participant_number' => 23,
            'assign_array' => json_encode([4,4,4,4,4,3]),
        ],[
            'participant_number' => 24,
            'assign_array' => json_encode([4,4,4,4,4,4]),
        ],[
            'participant_number' => 25,
            'assign_array' => json_encode([4,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 26,
            'assign_array' => json_encode([4,4,3,3,3,3,3,3]),
        ],[
            'participant_number' => 27,
            'assign_array' => json_encode([4,4,4,3,3,3,3,3]),
        ],[
            'participant_number' => 28,
            'assign_array' => json_encode([4,4,4,4,3,3,3,3]),
        ],[
            'participant_number' => 29,
            'assign_array' => json_encode([4,4,4,4,4,3,3,3]),
        ],[
            'participant_number' => 30,
            'assign_array' => json_encode([4,4,4,4,4,4,3,3]),
        ],[
            'participant_number' => 31,
            'assign_array' => json_encode([4,4,4,4,4,4,4,3]),
        ],[
            'participant_number' => 32,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4]),
        ],[
            'participant_number' => 33,
            'assign_array' => json_encode([4,4,4,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 34,
            'assign_array' => json_encode([4,4,4,4,3,3,3,3,3,3]),
        ],[
            'participant_number' => 35,
            'assign_array' => json_encode([4,4,4,4,4,3,3,3,3,3]),
        ],[
            'participant_number' => 36,
            'assign_array' => json_encode([4,4,4,4,4,4,3,3,3,3]),
        ],[
            'participant_number' => 37,
            'assign_array' => json_encode([4,4,4,4,4,4,4,3,3,3]),
        ],[
            'participant_number' => 38,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,3,3]),
        ],[
            'participant_number' => 39,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,3]),
        ],[
            'participant_number' => 40,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4]),
        ],[
            'participant_number' => 41,
            'assign_array' => json_encode([4,4,4,4,4,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 42,
            'assign_array' => json_encode([4,4,4,4,4,4,3,3,3,3,3,3]),
        ],[
            'participant_number' => 43,
            'assign_array' => json_encode([4,4,4,4,4,4,4,3,3,3,3,3]),
        ],[
            'participant_number' => 44,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,3,3,3,3]),
        ],[
            'participant_number' => 45,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,3,3,3]),
        ],[
            'participant_number' => 46,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,3,3]),
        ],[
            'participant_number' => 47,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,3]),
        ],[
            'participant_number' => 48,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4]),
        ],[
            'participant_number' => 49,
            'assign_array' => json_encode([4,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 50,
            'assign_array' => json_encode([4,4,3,3,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 51,
            'assign_array' => json_encode([4,4,4,3,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 52,
            'assign_array' => json_encode([4,4,4,4,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 53,
            'assign_array' => json_encode([4,4,4,4,4,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 54,
            'assign_array' => json_encode([4,4,4,4,4,4,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 55,
            'assign_array' => json_encode([4,4,4,4,4,4,4,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 56,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 57,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 58,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,3,3,3,3,3,3]),
        ],[
            'participant_number' => 59,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,3,3,3,3,3]),
        ],[
            'participant_number' => 60,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,3,3,3,3]),
        ],[
            'participant_number' => 61,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,3,3,3]),
        ],[
            'participant_number' => 62,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,3,3]),
        ],[
            'participant_number' => 63,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,3]),
        ],[
            'participant_number' => 64,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4]),
        ],[
            'participant_number' => 65,
            'assign_array' => json_encode([4,4,4,4,4,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 66,
            'assign_array' => json_encode([4,4,4,4,4,4,3,3,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 67,
            'assign_array' => json_encode([4,4,4,4,4,4,4,3,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 68,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,3,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 69,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,3,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 70,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,3,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 71,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,3,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 72,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,3,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 73,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,3,3,3,3,3,3,3]),
        ],[
            'participant_number' => 74,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,3,3,3,3,3,3]),
        ],[
            'participant_number' => 75,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,3,3,3,3,3]),
        ],[
            'participant_number' => 76,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,3,3,3,3]),
        ],[
            'participant_number' => 77,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,3,3,3]),
        ],[
            'participant_number' => 78,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,3,3]),
        ],[
            'participant_number' => 79,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,3]),
        ],[
            'participant_number' => 80,
            'assign_array' => json_encode([4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4]),
        ],]);
    }
}
