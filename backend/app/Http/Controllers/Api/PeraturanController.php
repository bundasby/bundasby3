<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peraturan;
use Illuminate\Http\Request;

class PeraturanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peraturan::query();

        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        return response()->json($query->orderBy('tahun', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'required|string',
            'file_type' => 'nullable|string|max:50',
            'nomor_peraturan' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer|min:1900|max:2100',
            'is_active' => 'boolean',
        ]);

        $peraturan = Peraturan::create($validated);

        return response()->json($peraturan, 201);
    }

    public function show(Peraturan $peraturan)
    {
        return response()->json($peraturan);
    }

    public function update(Request $request, Peraturan $peraturan)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'sometimes|string',
            'file_type' => 'nullable|string|max:50',
            'nomor_peraturan' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer|min:1900|max:2100',
            'is_active' => 'boolean',
        ]);

        $peraturan->update($validated);

        return response()->json($peraturan);
    }

    public function destroy(Peraturan $peraturan)
    {
        $peraturan->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
