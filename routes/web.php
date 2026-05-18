<?php

use Illuminate\Support\Facades\Route;
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
    // ពិនិត្យមើលបើមាន Email នេះហើយ វានឹងមិនបង្កើតជាន់គ្នាទេ
    $userExists = User::where('email', 'lionelheng799@gmail.com')->exists();
    if ($userExists) {
        return "Admin user already exists in the database!";
    }

    User::create([
        'name' => 'Admin Kravan',
        'email' => 'lionelheng799@gmail.com',
        'password' => Hash::make('kravan2026'), // បងអាចប្តូរលេខសម្ងាត់ត្រង់នេះបាន
    ]);
    return "Admin User created successfully! Username: lionelheng799@gmail.com | Password: kravan2026";
});
Route::get('/', function () {
    return view('welcome');
});
// Route សម្រាប់ទាញយក Error Log មកបង្ហាញលើអេក្រង់
Route::get('/view-log', function () {
    $logPath = storage_path('logs/laravel.log');
    
    if (!file_exists($logPath)) {
        return "Log file does not exist yet.";
    }

    $file = file($logPath);
    // ចាប់យកអក្សរ ៥០ ជួរចុងក្រោយបង្អស់
    $lastLines = array_slice($file, -50); 
    
    echo "<pre>" . implode("", $lastLines) . "</pre>";
});