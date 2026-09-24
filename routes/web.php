<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout']);

// Protected Web Dashboard (Requires user login)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

// Interactive API Documentation & Testing (Scalar & Swagger)
Route::get('/docs', [\App\Http\Controllers\Api\DocsController::class, 'scalar'])->name('docs');
Route::get('/swagger', [\App\Http\Controllers\Api\DocsController::class, 'swagger'])->name('swagger');
Route::get('/docs/openapi.json', [\App\Http\Controllers\Api\DocsController::class, 'openapi'])->name('docs.openapi');

