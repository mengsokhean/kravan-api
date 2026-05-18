<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ====== Home ======
Route::get('/', function () {
    return response()->json(['status' => '✅ Laravel API is running!']);
});

// ====== Debug Database Connection ======
Route::get('/debug-db', function () {
    $output = [];

    $output['DB_HOST']           = env('DB_HOST', '❌ NOT SET');
    $output['DB_PORT']           = env('DB_PORT', '❌ NOT SET');
    $output['DB_DATABASE']       = env('DB_DATABASE', '❌ NOT SET');
    $output['DB_USERNAME']       = env('DB_USERNAME', '❌ NOT SET');
    $output['DB_PASSWORD']       = env('DB_PASSWORD') ? '✅ SET (hidden)' : '❌ NOT SET';
    $output['MYSQL_ATTR_SSL_CA'] = env('MYSQL_ATTR_SSL_CA', '❌ NOT SET');

    $sslPath = env('MYSQL_ATTR_SSL_CA');
    $output['SSL_FILE_EXISTS'] = $sslPath
        ? (file_exists($sslPath) ? '✅ File found!' : '❌ FILE NOT FOUND at: ' . $sslPath)
        : '❌ Path is empty';

    try {
        DB::connection()->getPdo();
        $output['DB_CONNECTION'] = '✅ Connected successfully!';
        $output['DB_VERSION']    = DB::select('SELECT VERSION() as version')[0]->version;
    } catch (\Exception $e) {
        $output['DB_CONNECTION'] = '❌ FAILED';
        $output['ERROR_MESSAGE'] = $e->getMessage();
        $output['ERROR_CLASS']   = get_class($e);
    }

    return response()->json($output, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// ====== Run Migration (មាន​ error capture) ======
Route::get('/run-migrate', function () {
    try {
        $exitCode = Artisan::call('migrate', ['--force' => true]);
        $output   = Artisan::output();

        return response()->json([
            'status'    => $exitCode === 0 ? '✅ Success' : '⚠️ Finished with warnings',
            'exit_code' => $exitCode,
            'output'    => $output,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'        => '❌ Migration FAILED',
            'error_message' => $e->getMessage(),
            'error_class'   => get_class($e),
            'error_file'    => $e->getFile(),
            'error_line'    => $e->getLine(),
        ], 500);
    }
});

// ====== Create Admin User ======
Route::get('/create-admin', function () {
    $userExists = User::where('email', 'lionelheng799@gmail.com')->exists();
    if ($userExists) {
        return response()->json(['status' => '⚠️ Admin user already exists!']);
    }

    User::create([
        'name'     => 'Admin Kravan',
        'email'    => 'lionelheng799@gmail.com',
        'password' => Hash::make('kravan2026'),
    ]);

    return response()->json(['status' => '✅ Admin User created!', 'email' => 'lionelheng799@gmail.com']);
});

// ====== View Laravel Log ======
Route::get('/view-log', function () {
    $logPath = storage_path('logs/laravel.log');

    if (!file_exists($logPath)) {
        return "Log file does not exist yet.";
    }

    $lastLines = array_slice(file($logPath), -50);
    echo "<pre>" . implode("", $lastLines) . "</pre>";
});