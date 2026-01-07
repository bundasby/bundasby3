<?php

namespace App\Exports;

use App\Models\MitraPaud;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MitraPaudExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return MitraPaud::where('status', 'approved')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Organisasi',
            'Kategori',
            'Nama Pimpinan',
            'Alamat',
            'Telepon',
            'Email',
            'Website',
            'Jenis Kerjasama',
        ];
    }

    public function map($mitra): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $mitra->nama_organisasi,
            $mitra->kategori_organisasi,
            $mitra->nama_pimpinan,
            $mitra->alamat,
            $mitra->telepon,
            $mitra->email,
            $mitra->website,
            is_array($mitra->jenis_kerjasama) ? implode(', ', $mitra->jenis_kerjasama) : $mitra->jenis_kerjasama,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
