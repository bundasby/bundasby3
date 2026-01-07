<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $query = Materi::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'required|string',
            'file_type' => 'nullable|string|max:50',
            'file_size' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $materi = Materi::create($validated);

        return response()->json($materi, 201);
    }

    public function show(Materi $materi)
    {
        return response()->json($materi);
    }

    public function update(Request $request, Materi $materi)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'sometimes|string',
            'file_type' => 'nullable|string|max:50',
            'file_size' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $materi->update($validated);

        return response()->json($materi);
    }

    public function destroy(Materi $materi)
    {
        $materi->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
