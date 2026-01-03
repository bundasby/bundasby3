<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * Display a listing of sliders (Public - active only)
     */
    public function index()
    {
        $sliders = Slider::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $sliders,
        ]);
    }

    /**
     * Display all sliders for admin
     */
    public function all()
    {
        $sliders = Slider::ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $sliders,
        ]);
    }

    /**
     * Store a newly created slider
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string',
            'image' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $slider = Slider::create([
            'title' => $request->title,
            'caption' => $request->caption,
            'image' => $request->image,
            'order' => $request->order ?? Slider::max('order') + 1,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider berhasil ditambahkan',
            'data' => $slider,
        ], 201);
    }

    /**
     * Display the specified slider
     */
    public function show(Slider $slider)
    {
        return response()->json([
            'success' => true,
            'data' => $slider,
        ]);
    }

    /**
     * Update the specified slider
     */
    public function update(Request $request, Slider $slider)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'caption' => 'nullable|string',
            'image' => 'sometimes|required|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $slider->update($request->only(['title', 'caption', 'image', 'order', 'is_active']));

        return response()->json([
            'success' => true,
            'message' => 'Slider berhasil diperbarui',
            'data' => $slider,
        ]);
    }

    /**
     * Remove the specified slider
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slider berhasil dihapus',
        ]);
    }

    /**
     * Toggle slider active status
     */
    public function toggleActive(Slider $slider)
    {
        $slider->update(['is_active' => !$slider->is_active]);

        return response()->json([
            'success' => true,
            'message' => $slider->is_active ? 'Slider diaktifkan' : 'Slider dinonaktifkan',
            'data' => $slider,
        ]);
    }
}
