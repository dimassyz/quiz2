<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\ValidationController;
use App\Http\Controllers\Api\V1\JobVacancyController;
use App\Http\Controllers\Api\V1\ApplicationController; // Pastikan ini di-import

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // RUTE PUBLIK
    Route::post('/auth/login', [AuthController::class, 'login']);

    // RUTE YANG DILINDUNGI
    Route::middleware('auth:sanctum')->group(function () {

        // Autentikasi
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Dashboard
        Route::get('/applications', [DashboardController::class, 'getApplications']);

        // Validasi Data
        Route::get('/validation', [ValidationController::class, 'show']);
        Route::post('/validation', [ValidationController::class, 'store']);
        Route::get('/job_categories', [ValidationController::class, 'getJobCategories']);

        // Lowongan Pekerjaan
        Route::get('/job_vacancies', [JobVacancyController::class, 'index']);
        Route::get('/job_vacancies/{vacancy}', [JobVacancyController::class, 'show']);

        // Lamaran Pekerjaan
        // POST /applications: Mengirim lamaran baru
        Route::post('/applications', [ApplicationController::class, 'store']);
    });
});
