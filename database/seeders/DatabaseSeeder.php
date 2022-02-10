<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            ModalitySeeder::class,
            StatusSeeder::class,
            CompetitionTypeSeeder::class,
            LycraSeeder::class,
            SexSeeder::class,
            CategorySeeder::class,
            ClubSeeder::class,
            RankingSeeder::class,
            RankingPositionPointSeeder::class,
            HeatConfigurationSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
