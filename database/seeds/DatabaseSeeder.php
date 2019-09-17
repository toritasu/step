<?php

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
        $this->call(StepsTableSeeder::class);
        $this->call(SubstepsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ChallengesTableSeeder::class);
    }
}
