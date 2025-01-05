<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(5)->create();
        if(User::count() == 0)
            User::factory()->create(['name' => 'Usman Jalil', 'email' => 'usman@usman.com']);
            User::factory()->create(['name' => 'Umair Khan', 'email' => 'umair@khan.com']);
            User::factory()->create(['name' => 'Ali Ahmed', 'email' => 'ali@ahmed.com']);
            User::factory()->create(['name' => 'Zeeshan Malik', 'email' => 'zeeshan@malik.com']);
            User::factory()->create(['name' => 'Asim Raza', 'email' => 'asim@raza.com']);
            User::factory()->create(['name' => 'Hamza Farooq', 'email' => 'hamza@farooq.com']);
            User::factory()->create(['name' => 'Sajjad Hussain', 'email' => 'sajjad@hussain.com']);
            User::factory()->create(['name' => 'Umar Khalid', 'email' => 'umar@khalid.com']);
            User::factory()->create(['name' => 'Wahaaj Rehman', 'email' => 'wahaaj@rehman.com']);
            User::factory()->create(['name' => 'Jalaal Khan', 'email' => 'jalaal@khan.com']);
            User::factory()->create(['name' => 'Ateeq Ahmed', 'email' => 'ateeq@ahmed.com']);
            User::factory()->create(['name' => 'Faizan Ali', 'email' => 'faizan@ali.com']);
            User::factory()->create(['name' => 'Bilal Saeed', 'email' => 'bilal@saeed.com']);
            User::factory()->create(['name' => 'Noman Tariq', 'email' => 'noman@tariq.com']);
            User::factory()->create(['name' => 'Shahzad Ali', 'email' => 'shahzad@ali.com']);
    }
}
