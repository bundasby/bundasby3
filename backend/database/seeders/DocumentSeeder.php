<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'title' => 'SK Pengangkatan Pokja Bunda PAUD 2025-2030',
                'description' => 'Surat Keputusan pengangkatan pengurus Pokja Bunda PAUD Kota Surabaya periode 2025-2030',
                'file_url' => '/storage/documents/sk-pokja-2025.pdf',
                'file_type' => 'PDF',
                'file_size' => 2500000,
                'category' => 'regulasi',
            ],
            [
                'title' => 'Laporan Kegiatan Konferensi Bunda PAUD 2025',
                'description' => 'Laporan lengkap pelaksanaan konferensi tahunan',
                'file_url' => '/storage/documents/laporan-konferensi-2025.pdf',
                'file_type' => 'PDF',
                'file_size' => 5200000,
                'category' => 'kegiatan',
            ],
            [
                'title' => 'Rencana Anggaran Biaya 2025',
                'description' => 'RAB kegiatan Bunda PAUD Kota Surabaya tahun 2025',
                'file_url' => '/storage/documents/rab-2025.xlsx',
                'file_type' => 'XLSX',
                'file_size' => 1800000,
                'category' => 'anggaran',
            ],
            [
                'title' => 'Laporan Monitoring Program MBG Q4 2025',
                'description' => 'Hasil monitoring dan evaluasi program MBG kuartal 4',
                'file_url' => '/storage/documents/monev-mbg-q4-2025.pdf',
                'file_type' => 'PDF',
                'file_size' => 3100000,
                'category' => 'monitoring',
            ],
            [
                'title' => 'Pedoman Penyelenggaraan PAUD Holistik Integratif',
                'description' => 'Panduan untuk lembaga PAUD dalam menerapkan pendekatan holistik integratif',
                'file_url' => '/storage/documents/pedoman-paud-hi.pdf',
                'file_type' => 'PDF',
                'file_size' => 4700000,
                'category' => 'regulasi',
            ],
        ];

        foreach ($documents as $document) {
            Document::create([
                ...$document,
                'is_active' => true,
            ]);
        }
    }
}
