<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Route សម្រាប់រត់ Migration
Route::get('/run-migrate', function () {
    Artisan::call('migrate:fresh', ['--force' => true]);
    return "Migration finished successfully!";
});

// Route សម្រាប់បង្កើត User ថ្មីដើម្បី Login (Seed User)
Route::get('/create-admin', function () {
    $user = User::create([
        'name' => 'Admin Kravan',
        'email' => 'lionelheng799@gmail.com',
        'password' => Hash::make('thyseng1333'), // បងអាចដូរ password ត្រង់នេះបាន
    ]);
    return "Admin User created successfully! You can now login.";
});
