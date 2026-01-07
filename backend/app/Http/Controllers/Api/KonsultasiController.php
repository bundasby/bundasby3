<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Konsultasi::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email',
            'topik' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'deskripsi' => 'nullable|string',
        ]);

        $konsultasi = Konsultasi::create($validated);

        return response()->json($konsultasi, 201);
    }

    public function show(Konsultasi $konsultasi)
    {
        return response()->json($konsultasi);
    }

    public function update(Request $request, Konsultasi $konsultasi)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        $konsultasi->update($validated);

        return response()->json($konsultasi);
    }

    public function destroy(Konsultasi $konsultasi)
    {
        $konsultasi->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function approved()
    {
        return response()->json(
            Konsultasi::where('status', 'approved')->orderBy('tanggal')->get()
        );
    }
}
