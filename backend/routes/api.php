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
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\UserController;
// New Controllers
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\KonsultasiController;
use App\Http\Controllers\Api\InfografisController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\MajalahController;
use App\Http\Controllers\Api\BundaTvController;
use App\Http\Controllers\Api\PeraturanController;
use App\Http\Controllers\Api\NpkController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\MitraPaudController;
use App\Http\Controllers\Api\GugusTugasController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\HealthController;
// Webservice Integration Controllers
use App\Http\Controllers\Api\ExternalApiController;
use App\Http\Controllers\Api\ApiClientController;
use App\Http\Controllers\Api\PublicApiController;
use App\Http\Controllers\Api\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes - Bunda PAUD Kota Surabaya
|--------------------------------------------------------------------------
*/

// Health Check Routes (outside v1 prefix for easy access)
Route::get('/health', [HealthController::class, 'check']);
Route::get('/health/ping', [HealthController::class, 'ping']);
Route::get('/health/ready', [HealthController::class, 'ready']);
Route::get('/health/live', [HealthController::class, 'live']);

// Public Routes
Route::prefix('v1')->group(function () {

    // File Upload (public for now, can be protected later)
    Route::post('/upload', [FileUploadController::class, 'upload']);
    Route::delete('/upload', [FileUploadController::class, 'delete']);

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

    // Sliders (Public - for development, should be protected in production)
    Route::get('/sliders', [SliderController::class, 'index']);
    Route::get('/sliders/all', [SliderController::class, 'all']);
    Route::post('/sliders', [SliderController::class, 'store']);
    Route::get('/sliders/{slider}', [SliderController::class, 'show']);
    Route::put('/sliders/{slider}', [SliderController::class, 'update']);
    Route::delete('/sliders/{slider}', [SliderController::class, 'destroy']);
    Route::patch('/sliders/{slider}/toggle', [SliderController::class, 'toggleActive']);

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

    // Analytics (Public - for dashboard)
    Route::prefix('analytics')->group(function () {
        Route::get('/stats', [AnalyticsController::class, 'stats']);
        Route::get('/complaints-trend', [AnalyticsController::class, 'complaintsTrend']);
        Route::get('/bunda-paud-distribution', [AnalyticsController::class, 'bundaPaudDistribution']);
        Route::get('/recent-activity', [AnalyticsController::class, 'recentActivity']);
        Route::get('/mitra-by-category', [AnalyticsController::class, 'mitraByCategory']);
    });

    // Export Data (CSV)
    Route::prefix('export')->group(function () {
        Route::get('/bunda-paud', [ExportController::class, 'bundaPaud']);
        Route::get('/mitra-paud', [ExportController::class, 'mitraPaud']);
        Route::get('/complaints', [ExportController::class, 'complaints']);
        Route::get('/articles', [ExportController::class, 'articles']);
        // Excel exports (XLSX)
        Route::get('/bunda-paud/excel', [ExportController::class, 'bundaPaudExcel']);
        Route::get('/mitra-paud/excel', [ExportController::class, 'mitraPaudExcel']);
    });

    // =====================================================
    // NEW PUBLIC ROUTES
    // =====================================================

    // Categories (Public)
    Route::get('/categories', [CategoryController::class, 'index']);

    // Peminjaman (Public - submit only)
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    Route::get('/peminjaman/approved', [PeminjamanController::class, 'approved']);

    // Konsultasi (Public - submit only)
    Route::post('/konsultasi', [KonsultasiController::class, 'store']);
    Route::get('/konsultasi/approved', [KonsultasiController::class, 'approved']);

    // Infografis (Public)
    Route::get('/infografis', [InfografisController::class, 'index']);

    // Materi (Public)
    Route::get('/materi', [MateriController::class, 'index']);

    // Majalah (Public)
    Route::get('/majalah', [MajalahController::class, 'index']);

    // Bunda TV (Public)
    Route::get('/bunda-tv', [BundaTvController::class, 'index']);

    // Peraturan (Public)
    Route::get('/peraturan', [PeraturanController::class, 'index']);

    // NPK (Public)
    Route::get('/npk', [NpkController::class, 'index']);

    // Laporan (Public)
    Route::get('/laporan', [LaporanController::class, 'index']);

    // Mitra PAUD (Public - submit & approved list)
    Route::post('/mitra-paud', [MitraPaudController::class, 'store']);
    Route::get('/mitra-paud/approved', [MitraPaudController::class, 'approved']);

    // Gugus Tugas (Public)
    Route::get('/gugus-tugas', [GugusTugasController::class, 'index']);
});

// Protected Routes (Admin)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // User Management (Super Admin only)
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive']);
    });

    // Get roles (for dropdown - accessible to admin too)
    Route::get('/roles', [UserController::class, 'roles']);

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

    // Profile Management (Admin)
    Route::post('/profiles', [ProfileController::class, 'store']);
    Route::put('/profiles/{key}', [ProfileController::class, 'update']);

    // =====================================================
    // NEW ADMIN ROUTES
    // =====================================================

    // Categories CRUD
    Route::apiResource('categories', CategoryController::class)->except(['index']);

    // Peminjaman CRUD (Admin)
    Route::get('/peminjaman', [PeminjamanController::class, 'index']);
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show']);
    Route::put('/peminjaman/{peminjaman}', [PeminjamanController::class, 'update']);
    Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy']);

    // Konsultasi CRUD (Admin)
    Route::get('/konsultasi', [KonsultasiController::class, 'index']);
    Route::get('/konsultasi/{konsultasi}', [KonsultasiController::class, 'show']);
    Route::put('/konsultasi/{konsultasi}', [KonsultasiController::class, 'update']);
    Route::delete('/konsultasi/{konsultasi}', [KonsultasiController::class, 'destroy']);

    // Infografis CRUD
    Route::post('/infografis', [InfografisController::class, 'store']);
    Route::get('/infografis/{infografi}', [InfografisController::class, 'show']);
    Route::put('/infografis/{infografi}', [InfografisController::class, 'update']);
    Route::delete('/infografis/{infografi}', [InfografisController::class, 'destroy']);

    // Materi CRUD
    Route::post('/materi', [MateriController::class, 'store']);
    Route::get('/materi/{materi}', [MateriController::class, 'show']);
    Route::put('/materi/{materi}', [MateriController::class, 'update']);
    Route::delete('/materi/{materi}', [MateriController::class, 'destroy']);

    // Majalah CRUD
    Route::post('/majalah', [MajalahController::class, 'store']);
    Route::get('/majalah/{majalah}', [MajalahController::class, 'show']);
    Route::put('/majalah/{majalah}', [MajalahController::class, 'update']);
    Route::delete('/majalah/{majalah}', [MajalahController::class, 'destroy']);

    // Bunda TV CRUD
    Route::post('/bunda-tv', [BundaTvController::class, 'store']);
    Route::get('/bunda-tv/{bundaTv}', [BundaTvController::class, 'show']);
    Route::put('/bunda-tv/{bundaTv}', [BundaTvController::class, 'update']);
    Route::delete('/bunda-tv/{bundaTv}', [BundaTvController::class, 'destroy']);

    // Peraturan CRUD
    Route::post('/peraturan', [PeraturanController::class, 'store']);
    Route::get('/peraturan/{peraturan}', [PeraturanController::class, 'show']);
    Route::put('/peraturan/{peraturan}', [PeraturanController::class, 'update']);
    Route::delete('/peraturan/{peraturan}', [PeraturanController::class, 'destroy']);

    // NPK CRUD
    Route::post('/npk', [NpkController::class, 'store']);
    Route::get('/npk/{npk}', [NpkController::class, 'show']);
    Route::put('/npk/{npk}', [NpkController::class, 'update']);
    Route::delete('/npk/{npk}', [NpkController::class, 'destroy']);

    // Laporan CRUD
    Route::post('/laporan', [LaporanController::class, 'store']);
    Route::get('/laporan/{laporan}', [LaporanController::class, 'show']);
    Route::put('/laporan/{laporan}', [LaporanController::class, 'update']);
    Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy']);

    // Mitra PAUD CRUD (Admin)
    Route::get('/mitra-paud', [MitraPaudController::class, 'index']);
    Route::get('/mitra-paud/{mitraPaud}', [MitraPaudController::class, 'show']);
    Route::put('/mitra-paud/{mitraPaud}', [MitraPaudController::class, 'update']);
    Route::delete('/mitra-paud/{mitraPaud}', [MitraPaudController::class, 'destroy']);

    // Gugus Tugas CRUD
    Route::post('/gugus-tugas', [GugusTugasController::class, 'store']);
    Route::get('/gugus-tugas/{gugusTuga}', [GugusTugasController::class, 'show']);
    Route::put('/gugus-tugas/{gugusTuga}', [GugusTugasController::class, 'update']);
    Route::delete('/gugus-tugas/{gugusTuga}', [GugusTugasController::class, 'destroy']);

    // =====================================================
    // WEBSERVICE INTEGRATION ROUTES (Admin)
    // =====================================================

    // External API Management
    Route::prefix('external-apis')->group(function () {
        Route::get('/', [ExternalApiController::class, 'index']);
        Route::post('/', [ExternalApiController::class, 'store']);
        Route::get('/{externalApi}', [ExternalApiController::class, 'show']);
        Route::put('/{externalApi}', [ExternalApiController::class, 'update']);
        Route::delete('/{externalApi}', [ExternalApiController::class, 'destroy']);
        Route::post('/{externalApi}/test', [ExternalApiController::class, 'test']);
        Route::post('/{externalApi}/sync', [ExternalApiController::class, 'sync']);
        Route::post('/fetch', [ExternalApiController::class, 'fetch']);
    });

    // API Client Management
    Route::prefix('api-clients')->group(function () {
        Route::get('/', [ApiClientController::class, 'index']);
        Route::post('/', [ApiClientController::class, 'store']);
        Route::get('/endpoints', [ApiClientController::class, 'availableEndpoints']);
        Route::get('/{apiClient}', [ApiClientController::class, 'show']);
        Route::put('/{apiClient}', [ApiClientController::class, 'update']);
        Route::delete('/{apiClient}', [ApiClientController::class, 'destroy']);
        Route::post('/{apiClient}/regenerate-key', [ApiClientController::class, 'regenerateKey']);
        Route::get('/{apiClient}/logs', [ApiClientController::class, 'logs']);
    });

    // Webhook Management
    Route::prefix('webhooks')->group(function () {
        Route::get('/', [WebhookController::class, 'index']);
        Route::post('/', [WebhookController::class, 'store']);
        Route::get('/events', [WebhookController::class, 'events']);
        Route::get('/statistics', [WebhookController::class, 'statistics']);
        Route::post('/retry-failed', [WebhookController::class, 'retryFailed']);
        Route::get('/{webhook}', [WebhookController::class, 'show']);
        Route::put('/{webhook}', [WebhookController::class, 'update']);
        Route::delete('/{webhook}', [WebhookController::class, 'destroy']);
        Route::post('/{webhook}/test', [WebhookController::class, 'test']);
        Route::get('/{webhook}/logs', [WebhookController::class, 'logs']);
        Route::post('/{webhook}/regenerate-secret', [WebhookController::class, 'regenerateSecret']);
    });
});

// =====================================================
// PUBLIC API (For External Dinas - API Key Auth)
// =====================================================
Route::prefix('public/v1')->middleware('api.key')->group(function () {
    Route::get('/health', [PublicApiController::class, 'healthCheck']);
    Route::get('/data-paud', [PublicApiController::class, 'dataPaud']);
    Route::get('/data-paud/{npsn}', [PublicApiController::class, 'dataPaudByNpsn']);
    Route::get('/bunda-paud', [PublicApiController::class, 'bundaPaud']);
    Route::get('/mitra-paud', [PublicApiController::class, 'mitraPaud']);
    Route::get('/statistics', [PublicApiController::class, 'statistics']);
    Route::get('/articles', [PublicApiController::class, 'articles']);
    Route::get('/programs', [PublicApiController::class, 'programs']);
});
