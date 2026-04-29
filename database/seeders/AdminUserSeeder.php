<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
       User::updateOrCreate(
    ['email' => 'lionelheng799@gmail.com'],  // ← email ថ្មី
    [
        'name'     => 'Seng Thy',             // ← ឈ្មោះថ្មី
        'email'    => 'lionelheng799@gmail.com',
        'password' => Hash::make('Sengthy1333'), // ← password ថ្មី
    ]
);
        echo "✅ Admin created: lionelheng799@gmail.com / Sengthy1333\n";
    }
}