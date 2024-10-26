<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 new users
        // User::factory()->count(1)->create();

        // Create a single user with specific details
        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
        ]);
        
    }
}
