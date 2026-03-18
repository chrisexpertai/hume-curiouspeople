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
        // Call other seeders here
        $this->call(UsersTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        // Add more seeder calls as needed
    }
}
