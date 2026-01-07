<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'keperluan' => 'required|string',
            'jumlah_peserta' => 'nullable|integer',
        ]);

        $peminjaman = Peminjaman::create($validated);

        return response()->json($peminjaman, 201);
    }

    public function show(Peminjaman $peminjaman)
    {
        return response()->json($peminjaman);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman->update($validated);

        return response()->json($peminjaman);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function approved()
    {
        return response()->json(
            Peminjaman::where('status', 'approved')->orderBy('tanggal_mulai')->get()
        );
    }
}
