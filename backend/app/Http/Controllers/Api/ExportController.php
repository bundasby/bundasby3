<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BundaPaud;
use App\Models\MitraPaud;
use App\Models\Complaint;
use App\Models\Article;
use App\Exports\BundaPaudExport;
use App\Exports\MitraPaudExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export Bunda PAUD to Excel (XLSX)
     */
    public function bundaPaudExcel()
    {
        return Excel::download(new BundaPaudExport, 'bunda_paud_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Mitra PAUD to Excel (XLSX)
     */
    public function mitraPaudExcel()
    {
        return Excel::download(new MitraPaudExport, 'mitra_paud_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Bunda PAUD to CSV
     */
    public function bundaPaud()
    {
        $data = BundaPaud::where('is_active', true)->get();

        $filename = 'bunda_paud_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['No', 'Nama', 'Jabatan', 'Wilayah', 'Tipe', 'Telepon', 'Email'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            $no = 1;
            foreach ($data as $item) {
                fputcsv($file, [
                    $no++,
                    $item->name,
                    $item->position,
                    $item->area,
                    ucfirst($item->type),
                    $item->phone,
                    $item->email
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Mitra PAUD to CSV
     */
    public function mitraPaud()
    {
        $data = MitraPaud::where('status', 'approved')->get();

        $filename = 'mitra_paud_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['No', 'Nama Organisasi', 'Kategori', 'Nama Pimpinan', 'Alamat', 'Telepon', 'Email', 'Website', 'Jenis Kerjasama'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            $no = 1;
            foreach ($data as $item) {
                fputcsv($file, [
                    $no++,
                    $item->nama_organisasi,
                    $item->kategori_organisasi,
                    $item->nama_pimpinan,
                    $item->alamat,
                    $item->telepon,
                    $item->email,
                    $item->website,
                    is_array($item->jenis_kerjasama) ? implode(', ', $item->jenis_kerjasama) : $item->jenis_kerjasama
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Complaints to CSV
     */
    public function complaints()
    {
        $data = Complaint::orderBy('created_at', 'desc')->get();

        $filename = 'pengaduan_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['No', 'Tanggal', 'Nama', 'Email', 'Telepon', 'Subjek', 'Isi Pengaduan', 'Status', 'Response'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            $no = 1;
            foreach ($data as $item) {
                fputcsv($file, [
                    $no++,
                    $item->created_at->format('d/m/Y H:i'),
                    $item->name,
                    $item->email,
                    $item->phone,
                    $item->subject ?? '-',
                    $item->message,
                    ucfirst($item->status),
                    $item->response ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Articles to CSV
     */
    public function articles()
    {
        $data = Article::orderBy('created_at', 'desc')->get();

        $filename = 'artikel_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['No', 'Judul', 'Kategori', 'Author', 'Tanggal Publish', 'Status'];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            $no = 1;
            foreach ($data as $item) {
                fputcsv($file, [
                    $no++,
                    $item->title,
                    $item->category->name ?? '-',
                    $item->author,
                    $item->published_at ? $item->published_at->format('d/m/Y') : '-',
                    $item->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
