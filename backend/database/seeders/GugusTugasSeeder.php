<?php

namespace Database\Seeders;

use App\Models\GugusTugas;
use Illuminate\Database\Seeder;

class GugusTugasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Eri Cahyadi',
                'jabatan' => 'Ketua Gugus Tugas PAUD HI',
                'instansi' => 'Pemerintah Kota Surabaya',
                'foto_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop',
                'kategori' => 'kota',
                'wilayah' => 'Surabaya',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'nama' => 'Dr. Nanik Sri Setyani',
                'jabatan' => 'Wakil Ketua Gugus Tugas',
                'instansi' => 'Dinas Pendidikan',
                'foto_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop',
                'kategori' => 'kota',
                'wilayah' => 'Surabaya',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Kepala Kecamatan Genteng',
                'jabatan' => 'Koordinator Kecamatan',
                'instansi' => 'Kecamatan Genteng',
                'foto_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&h=300&fit=crop',
                'kategori' => 'kecamatan',
                'wilayah' => 'Genteng',
                'order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            GugusTugas::firstOrCreate(
                ['nama' => $item['nama'], 'kategori' => $item['kategori']],
                $item
            );
        }
    }
}
