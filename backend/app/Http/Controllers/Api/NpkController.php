<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Npk;
use Illuminate\Http\Request;

class NpkController extends Controller
{
    public function index(Request $request)
    {
        $query = Npk::query();

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
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $npk = Npk::create($validated);

        return response()->json($npk, 201);
    }

    public function show(Npk $npk)
    {
        return response()->json($npk);
    }

    public function update(Request $request, Npk $npk)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'sometimes|string',
            'file_type' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $npk->update($validated);

        return response()->json($npk);
    }

    public function destroy(Npk $npk)
    {
        $npk->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
