<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\BundaPaudController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes - Bunda PAUD Kota Surabaya
|--------------------------------------------------------------------------
*/

// Public Routes
Route::prefix('v1')->group(function () {

    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Settings (Public - read and write for now until DB is configured)
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::put('/settings', [SettingsController::class, 'update']);

    // Articles (Public)
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);

    // Galleries (Public)
    Route::get('/galleries', [GalleryController::class, 'index']);

    // Documents (Public)
    Route::get('/documents', [DocumentController::class, 'index']);

    // Complaints (Public - submit)
    Route::post('/complaints', [ComplaintController::class, 'store']);

    // Bunda PAUD (Public)
    Route::get('/bunda-paud', [BundaPaudController::class, 'index']);
    Route::get('/bunda-paud/kecamatan', [BundaPaudController::class, 'getKecamatanList']);

    // Sliders (Public - active only)
    Route::get('/sliders', [SliderController::class, 'index']);

    // Statistics (Public)
    Route::get('/statistics', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'total_paud' => 850,
                'total_guru' => 3200,
                'total_siswa' => 45000,
                'total_kecamatan' => 31,
                'stunting_rate' => '12%',
                'mbg_penerima' => 35000,
            ],
        ]);
    });

    // Profiles (Public & Admin)
    Route::get('/profiles', [ProfileController::class, 'index']);
    Route::get('/profiles/all', [ProfileController::class, 'all'])->middleware('auth:sanctum');
    Route::get('/profiles/{key}', [ProfileController::class, 'show']);
});

// Protected Routes (Admin)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Articles CRUD
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{article}', [ArticleController::class, 'update']);
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);

    // Galleries CRUD
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::put('/galleries/{gallery}', [GalleryController::class, 'update']);
    Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy']);

    // Documents CRUD
    Route::post('/documents', [DocumentController::class, 'store']);
    Route::put('/documents/{document}', [DocumentController::class, 'update']);
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy']);

    // Bunda PAUD CRUD (Admin)
    Route::post('/bunda-paud', [BundaPaudController::class, 'store']);
    Route::get('/bunda-paud/{bundaPaud}', [BundaPaudController::class, 'show']);
    Route::put('/bunda-paud/{bundaPaud}', [BundaPaudController::class, 'update']);
    Route::delete('/bunda-paud/{bundaPaud}', [BundaPaudController::class, 'destroy']);

    // Complaints (Admin)
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show']);
    Route::put('/complaints/{complaint}', [ComplaintController::class, 'update']);

    // Sliders CRUD (Admin)
    Route::get('/sliders/all', [SliderController::class, 'all']);
    Route::post('/sliders', [SliderController::class, 'store']);
    Route::get('/sliders/{slider}', [SliderController::class, 'show']);
    Route::put('/sliders/{slider}', [SliderController::class, 'update']);
    Route::delete('/sliders/{slider}', [SliderController::class, 'destroy']);
    Route::patch('/sliders/{slider}/toggle', [SliderController::class, 'toggleActive']);

    // Profile Management (Admin)
    Route::post('/profiles', [ProfileController::class, 'store']);
    Route::put('/profiles/{key}', [ProfileController::class, 'update']);
});

