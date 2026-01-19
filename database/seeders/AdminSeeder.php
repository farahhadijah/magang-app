<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
// Password will be hashed by the User model mutator

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                // assign plain password; the model's setPasswordAttribute will hash it
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'first_login' => true,
            ]
        );
    }
}