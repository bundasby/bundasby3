<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPaudSeeder extends Seeder
{
    public function run(): void
    {
        // Sekolah
        $sekolah = [
            ['npsn' => '20532001', 'nama' => 'TK Negeri Pembina Surabaya', 'jenis' => 'TK', 'alamat' => 'Jl. Raya Darmo No. 123', 'kecamatan' => 'Wonokromo', 'status' => 'active', 'akreditasi' => 'A'],
            ['npsn' => '20532002', 'nama' => 'KB Tunas Bangsa', 'jenis' => 'KB', 'alamat' => 'Jl. Kapas Krampung No. 45', 'kecamatan' => 'Simokerto', 'status' => 'active', 'akreditasi' => 'B'],
            ['npsn' => '20532003', 'nama' => 'TPA Ceria Mandiri', 'jenis' => 'TPA', 'alamat' => 'Jl. Margorejo Indah No. 67', 'kecamatan' => 'Wonocolo', 'status' => 'active', 'akreditasi' => 'B'],
            ['npsn' => '20532004', 'nama' => 'TK ABA Surabaya', 'jenis' => 'TK', 'alamat' => 'Jl. Ahmad Yani No. 100', 'kecamatan' => 'Gayungan', 'status' => 'active', 'akreditasi' => 'A'],
            ['npsn' => '20532005', 'nama' => 'KB Melati Indah', 'jenis' => 'KB', 'alamat' => 'Jl. Rungkut Asri No. 22', 'kecamatan' => 'Rungkut', 'status' => 'active', 'akreditasi' => 'B'],
            ['npsn' => '20532006', 'nama' => 'PAUD Terpadu Harapan', 'jenis' => 'PAUD', 'alamat' => 'Jl. Mulyosari No. 15', 'kecamatan' => 'Mulyorejo', 'status' => 'active', 'akreditasi' => 'A'],
        ];
        foreach ($sekolah as $s) {
            DB::table('data_paud_sekolah')->insert(array_merge($s, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Akreditasi
        $akreditasi = [
            ['npsn' => '20532001', 'nama' => 'TK Negeri Pembina Surabaya', 'peringkat' => 'A', 'nilai' => 95, 'tanggal' => '2024-06-15', 'berlaku' => '2029-06-15'],
            ['npsn' => '20532002', 'nama' => 'KB Tunas Bangsa', 'peringkat' => 'B', 'nilai' => 85, 'tanggal' => '2024-03-20', 'berlaku' => '2029-03-20'],
            ['npsn' => '20532004', 'nama' => 'TK ABA Surabaya', 'peringkat' => 'A', 'nilai' => 92, 'tanggal' => '2023-11-10', 'berlaku' => '2028-11-10'],
        ];
        foreach ($akreditasi as $a) {
            DB::table('data_paud_akreditasi')->insert(array_merge($a, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Ijin Operasional
        $ijin = [
            ['npsn' => '20532001', 'nama' => 'TK Negeri Pembina Surabaya', 'no_sk' => '421.1/123/2024', 'tanggal_sk' => '2024-01-15', 'berlaku' => '2029-01-15', 'status' => 'active'],
            ['npsn' => '20532002', 'nama' => 'KB Tunas Bangsa', 'no_sk' => '421.1/124/2024', 'tanggal_sk' => '2024-02-20', 'berlaku' => '2029-02-20', 'status' => 'active'],
            ['npsn' => '20532003', 'nama' => 'TPA Ceria Mandiri', 'no_sk' => '421.1/125/2023', 'tanggal_sk' => '2023-06-10', 'berlaku' => '2028-06-10', 'status' => 'active'],
        ];
        foreach ($ijin as $i) {
            DB::table('data_paud_ijin')->insert(array_merge($i, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Profiling
        $profiling = [
            ['npsn' => '20532001', 'nama' => 'TK Negeri Pembina Surabaya', 'siswa' => 120, 'guru' => 8, 'ruang_kelas' => 6, 'tahun' => 2025],
            ['npsn' => '20532002', 'nama' => 'KB Tunas Bangsa', 'siswa' => 45, 'guru' => 3, 'ruang_kelas' => 2, 'tahun' => 2025],
            ['npsn' => '20532004', 'nama' => 'TK ABA Surabaya', 'siswa' => 95, 'guru' => 6, 'ruang_kelas' => 5, 'tahun' => 2025],
        ];
        foreach ($profiling as $p) {
            DB::table('data_paud_profiling')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
