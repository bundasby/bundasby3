<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BundaTv;
use Illuminate\Http\Request;

class BundaTvController extends Controller
{
    public function index(Request $request)
    {
        $query = BundaTv::query();

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
            'video_url' => 'required|string',
            'thumbnail_url' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $bundaTv = BundaTv::create($validated);

        return response()->json($bundaTv, 201);
    }

    public function show(BundaTv $bundaTv)
    {
        return response()->json($bundaTv);
    }

    public function update(Request $request, BundaTv $bundaTv)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'sometimes|string',
            'thumbnail_url' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $bundaTv->update($validated);

        return response()->json($bundaTv);
    }

    public function destroy(BundaTv $bundaTv)
    {
        $bundaTv->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
