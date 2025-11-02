<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MainAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if main admin already exists
        $existingAdmin = User::where('email', 'main.garalde@gmail.com')->first();

        if (!$existingAdmin) {
            User::create([
                'name' => 'Main Admin',
                'email' => 'main.garalde@gmail.com',
                'password' => Hash::make('Garalde#1.1'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }
    }
}
