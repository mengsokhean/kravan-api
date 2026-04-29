<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\Admin\ProjectController as AdminProjectController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────
// PUBLIC routes — No login required
// ─────────────────────────────────────────────

Route::post('/login', [AuthController::class, 'login']);

// Projects (public view)
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/featured', [ProjectController::class, 'featured']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);

// ─────────────────────────────────────────────
// PROTECTED routes — Must be logged in (Sanctum)
// ─────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Admin: Manage projects
    Route::prefix('admin')->group(function () {
        Route::post('/projects', [AdminProjectController::class, 'store']);
        Route::put('/projects/{id}', [AdminProjectController::class, 'update']);
        Route::delete('/projects/{id}', [AdminProjectController::class, 'destroy']);

        // Manage relations
        Route::post('/projects/{id}/cast', [AdminProjectController::class, 'addCast']);
        Route::post('/projects/{id}/awards', [AdminProjectController::class, 'addAward']);
        Route::post('/projects/{id}/producers', [AdminProjectController::class, 'addProducer']);
         Route::delete('/cast/{id}', [AdminProjectController::class, 'deleteCast']);
        Route::delete('/awards/{id}', [AdminProjectController::class, 'deleteAward']);
        Route::delete('/producers/{id}', [AdminProjectController::class, 'deleteProducer']);
    });
});