<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TasksController;
use Illuminate\Support\Facades\Route;

// Rutas públicas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de autenticación
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Rutas
    Route::apiResource('Projects', ProjectsController::class);
    Route::get('Projects/search', [ProjectsController::class, 'search'])->name('Projects.search');

    Route::apiResource('Tasks', TasksController::class);
    Route::get('Tasks/search', [TasksController::class, 'search'])->name('Tasks.search');
});
