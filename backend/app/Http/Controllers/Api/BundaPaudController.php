<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BundaPaud;
use Illuminate\Http\Request;

class BundaPaudController extends Controller
{
    public function index(Request $request)
    {
        $query = BundaPaud::query();

        // Filter by type
        if ($request->has('type')) {
            $query->byType($request->type);
        }

        // Filter by kecamatan
        if ($request->has('kecamatan')) {
            $query->byKecamatan($request->kecamatan);
        }

        // Only active by default
        if (!$request->has('show_all')) {
            $query->active();
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%");
            });
        }

        $bundaPaud = $query->orderBy('area')->get();

        return response()->json([
            'success' => true,
            'data' => $bundaPaud
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'string|max:255',
            'area' => 'required|string|max:255',
            'type' => 'required|in:kecamatan,kelurahan',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'photo_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $bundaPaud = BundaPaud::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data Bunda PAUD berhasil ditambahkan',
            'data' => $bundaPaud
        ], 201);
    }

    public function show(BundaPaud $bundaPaud)
    {
        return response()->json([
            'success' => true,
            'data' => $bundaPaud
        ]);
    }

    public function update(Request $request, BundaPaud $bundaPaud)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'position' => 'string|max:255',
            'area' => 'string|max:255',
            'type' => 'in:kecamatan,kelurahan',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'photo_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $bundaPaud->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data Bunda PAUD berhasil diperbarui',
            'data' => $bundaPaud
        ]);
    }

    public function destroy(BundaPaud $bundaPaud)
    {
        $bundaPaud->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Bunda PAUD berhasil dihapus'
        ]);
    }

    public function getKecamatanList()
    {
        $kecamatan = BundaPaud::where('type', 'kecamatan')
            ->active()
            ->distinct()
            ->pluck('area');

        return response()->json([
            'success' => true,
            'data' => $kecamatan
        ]);
    }
}
