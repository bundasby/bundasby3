<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Public: Get all active profiles (for frontend dictionary)
    public function index()
    {
        $profiles = Profile::where('is_active', true)->get();
        // Return as key-value map for easier frontend usage
        $data = $profiles->mapWithKeys(function ($item) {
            return [$item->section_key => $item];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // Admin: Get all profiles
    public function all()
    {
        $profiles = Profile::all();
        return response()->json([
            'success' => true,
            'data' => $profiles
        ]);
    }

    // Public: Get specific profile section
    public function show($key)
    {
        $profile = Profile::where('section_key', $key)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile section not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    // Admin: Update profile section (or create if not exists)
    public function update(Request $request, $key)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $profile = Profile::updateOrCreate(
            ['section_key' => $key],
            [
                'title' => $request->title,
                'content' => $request->content,
                'image' => $request->image,
                'is_active' => $request->is_active ?? true
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Profile section updated successfully',
            'data' => $profile
        ]);
    }

    // Admin: Store (Create new section key manually)
    public function store(Request $request)
    {
        $request->validate([
            'section_key' => 'required|string|unique:profiles,section_key',
            'title' => 'required|string',
            'content' => 'nullable|string',
        ]);

        $profile = Profile::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Profile section created',
            'data' => $profile
        ]);
    }
}
