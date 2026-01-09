<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\BundaPaud;
use App\Models\MitraPaud;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Bunda PAUD Kota Surabaya Public API",
 *     description="Public API for external government agencies to access PAUD data",
 *     @OA\Contact(
 *         email="admin@bundapaud.surabaya.go.id"
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-Key"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="apiSecret",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-Secret"
 * )
 */
class PublicApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/public/v1/data-paud",
     *     summary="Get all PAUD schools data",
     *     tags={"Data PAUD"},
     *     security={{"apiKey": {}, "apiSecret": {}}},
     *     @OA\Parameter(
     *         name="kecamatan",
     *         in="query",
     *         description="Filter by kecamatan",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status (aktif/tidak_aktif)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=50)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=429, description="Rate limit exceeded")
     * )
     */
    public function dataPaud(Request $request): JsonResponse
    {
        // This is a placeholder - implement based on your actual data structure
        // For now, returning sample structure
        $data = [
            'total_paud' => 850,
            'total_guru' => 3200,
            'total_siswa' => 45000,
            'paud_per_kecamatan' => [
                ['kecamatan' => 'Gubeng', 'count' => 45],
                ['kecamatan' => 'Sukolilo', 'count' => 52],
                ['kecamatan' => 'Mulyorejo', 'count' => 38],
                // ... more data
            ],
            'fetched_at' => now()->toIso8601String(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/public/v1/data-paud/{npsn}",
     *     summary="Get PAUD school data by NPSN",
     *     tags={"Data PAUD"},
     *     security={{"apiKey": {}, "apiSecret": {}}},
     *     @OA\Parameter(
     *         name="npsn",
     *         in="path",
     *         description="NPSN of the school",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="School not found")
     * )
     */
    public function dataPaudByNpsn(string $npsn): JsonResponse
    {
        // Placeholder implementation
        $data = [
            'npsn' => $npsn,
            'nama_sekolah' => 'PAUD Sample',
            'alamat' => 'Jl. Example No. 123',
            'kecamatan' => 'Gubeng',
            'kelurahan' => 'Airlangga',
            'status' => 'Aktif',
            'akreditasi' => 'A',
            'jumlah_guru' => 5,
            'jumlah_siswa' => 50,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/public/v1/bunda-paud",
     *     summary="Get Bunda PAUD data per kecamatan/kelurahan",
     *     tags={"Bunda PAUD"},
     *     security={{"apiKey": {}, "apiSecret": {}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by type (kecamatan/kelurahan)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function bundaPaud(Request $request): JsonResponse
    {
        $query = BundaPaud::query();

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('kecamatan')) {
            $query->where('kecamatan', $request->input('kecamatan'));
        }

        $data = $query->orderBy('kecamatan')
            ->orderBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'kecamatan' => $item->kecamatan,
                    'kelurahan' => $item->kelurahan,
                    'type' => $item->type,
                    'jabatan' => $item->jabatan,
                    'foto' => $item->foto,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/public/v1/mitra-paud",
     *     summary="Get approved Mitra PAUD data",
     *     tags={"Mitra PAUD"},
     *     security={{"apiKey": {}, "apiSecret": {}}},
     *     @OA\Parameter(
     *         name="kategori",
     *         in="query",
     *         description="Filter by category",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function mitraPaud(Request $request): JsonResponse
    {
        $query = MitraPaud::where('status', 'approved');

        if ($request->has('kategori')) {
            $query->where('kategori_organisasi', $request->input('kategori'));
        }

        $data = $query->orderBy('nama_organisasi')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_organisasi' => $item->nama_organisasi,
                    'kategori_organisasi' => $item->kategori_organisasi,
                    'alamat' => $item->alamat,
                    'email' => $item->email,
                    'website' => $item->website,
                    'tujuan_organisasi' => $item->tujuan_organisasi,
                    'jenis_kerjasama' => $item->jenis_kerjasama,
                    'logo' => $item->logo,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $data->count(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/public/v1/statistics",
     *     summary="Get PAUD statistics",
     *     tags={"Statistics"},
     *     security={{"apiKey": {}, "apiSecret": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total_paud", type="integer"),
     *                 @OA\Property(property="total_guru", type="integer"),
     *                 @OA\Property(property="total_siswa", type="integer"),
     *                 @OA\Property(property="total_kecamatan", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function statistics(): JsonResponse
    {
        $data = [
            'total_paud' => 850,
            'total_guru' => 3200,
            'total_siswa' => 45000,
            'total_kecamatan' => 31,
            'total_kelurahan' => 154,
            'total_bunda_paud_kecamatan' => BundaPaud::where('type', 'kecamatan')->count(),
            'total_bunda_paud_kelurahan' => BundaPaud::where('type', 'kelurahan')->count(),
            'total_mitra_paud' => MitraPaud::where('status', 'approved')->count(),
            'stunting_rate' => '12%',
            'mbg_penerima' => 35000,
            'updated_at' => now()->toIso8601String(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/public/v1/articles",
     *     summary="Get latest articles/news",
     *     tags={"Articles"},
     *     security={{"apiKey": {}, "apiSecret": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit results",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function articles(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 10);

        $query = Article::where('is_published', true)
            ->orderBy('created_at', 'desc');

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        $articles = $query->limit($limit)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'category' => $article->category,
                    'thumbnail' => $article->thumbnail,
                    'published_at' => $article->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $articles,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/public/v1/programs",
     *     summary="Get programs",
     *     tags={"Programs"},
     *     security={{"apiKey": {}, "apiSecret": {}}},
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function programs(): JsonResponse
    {
        // Placeholder - implement based on your program structure
        $programs = [
            [
                'id' => 1,
                'name' => 'Program PAUD HI',
                'description' => 'Program PAUD Holistik Integratif',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Gerakan 7 KAIH',
                'description' => 'Gerakan 7 Kebiasaan Anak Indonesia Hebat',
                'status' => 'active',
            ],
            [
                'id' => 3,
                'name' => 'Digitalisasi PAUD',
                'description' => 'Program Digitalisasi Pembelajaran PAUD',
                'status' => 'active',
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $programs,
        ]);
    }

    /**
     * API health check for public API.
     */
    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0.0',
        ]);
    }
}
