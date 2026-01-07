<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MitraPaud;
use App\Mail\MitraRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MitraPaudController extends Controller
{
    public function index(Request $request)
    {
        $query = MitraPaud::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('kategori')) {
            $query->where('kategori_organisasi', $request->kategori);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_responden' => 'required|string|max:255',
            'jabatan_responden' => 'nullable|string|max:255',
            'telepon_responden' => 'required|string|max:20',
            'nama_organisasi' => 'required|string|max:255',
            'kategori_organisasi' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|string|max:255',
            'nama_pimpinan' => 'nullable|string|max:255',
            'tujuan_organisasi' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'jenis_kerjasama' => 'nullable|array',
            'catatan_kerjasama' => 'nullable|string',
            'dokumen_komitmen_url' => 'nullable|string',
            'link_pendukung' => 'nullable|string',
        ]);

        $mitra = MitraPaud::create($validated);

        // Send email notification to admin
        try {
            $adminEmail = config('mail.admin_email', 'bundapaud@surabaya.go.id');
            Mail::to($adminEmail)->send(new MitraRegistrationNotification($mitra, 'new'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send mitra registration email: ' . $e->getMessage());
        }

        return response()->json($mitra, 201);
    }

    public function show(MitraPaud $mitraPaud)
    {
        return response()->json($mitraPaud);
    }

    public function update(Request $request, MitraPaud $mitraPaud)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'nama_responden' => 'sometimes|string|max:255',
            'jabatan_responden' => 'nullable|string|max:255',
            'telepon_responden' => 'sometimes|string|max:20',
            'nama_organisasi' => 'sometimes|string|max:255',
            'kategori_organisasi' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|string|max:255',
            'nama_pimpinan' => 'nullable|string|max:255',
            'tujuan_organisasi' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'jenis_kerjasama' => 'nullable|array',
            'catatan_kerjasama' => 'nullable|string',
            'dokumen_komitmen_url' => 'nullable|string',
            'link_pendukung' => 'nullable|string',
        ]);

        $oldStatus = $mitraPaud->status;
        $mitraPaud->update($validated);

        // Send email if status changed and mitra has email
        if (isset($validated['status']) && $oldStatus !== $validated['status'] && $mitraPaud->email) {
            try {
                $emailType = $validated['status'] === 'approved' ? 'approved' : 'rejected';
                Mail::to($mitraPaud->email)->send(new MitraRegistrationNotification($mitraPaud, $emailType));
            } catch (\Exception $e) {
                \Log::warning('Failed to send mitra status update email: ' . $e->getMessage());
            }
        }

        return response()->json($mitraPaud);
    }

    public function destroy(MitraPaud $mitraPaud)
    {
        $mitraPaud->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function approved()
    {
        return response()->json(
            MitraPaud::where('status', 'approved')->orderBy('nama_organisasi')->get()
        );
    }
}
