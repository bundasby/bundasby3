<?php

namespace App\Exports;

use App\Models\BundaPaud;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BundaPaudExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return BundaPaud::where('is_active', true)->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Jabatan',
            'Wilayah',
            'Tipe',
            'Telepon',
            'Email',
        ];
    }

    public function map($bundaPaud): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $bundaPaud->name,
            $bundaPaud->position,
            $bundaPaud->area,
            ucfirst($bundaPaud->type),
            $bundaPaud->phone,
            $bundaPaud->email,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
