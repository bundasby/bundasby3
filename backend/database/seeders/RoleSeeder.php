<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'code' => 'super_admin',
                'name' => 'Super Admin',
                'level' => 1,
                'description' => 'Akses penuh ke semua fitur termasuk manajemen pengguna',
                'permissions' => json_encode(['*']),
            ],
            [
                'code' => 'admin',
                'name' => 'Admin',
                'level' => 2,
                'description' => 'Akses ke semua konten dan pengaturan',
                'permissions' => json_encode(['content.*', 'settings.*']),
            ],
            [
                'code' => 'bunda_kota',
                'name' => 'Bunda PAUD Kota',
                'level' => 3,
                'description' => 'Dashboard dan laporan tingkat kota',
                'permissions' => json_encode(['dashboard.view', 'reports.kota']),
            ],
            [
                'code' => 'bunda_kecamatan',
                'name' => 'Bunda PAUD Kecamatan',
                'level' => 4,
                'description' => 'Dashboard dan laporan tingkat kecamatan',
                'permissions' => json_encode(['dashboard.view', 'reports.kecamatan']),
            ],
            [
                'code' => 'bunda_kelurahan',
                'name' => 'Bunda PAUD Kelurahan',
                'level' => 5,
                'description' => 'Dashboard dan laporan tingkat kelurahan',
                'permissions' => json_encode(['dashboard.view', 'reports.kelurahan']),
            ],
            [
                'code' => 'gugus_tugas',
                'name' => 'Gugus Tugas PAUD HI',
                'level' => 6,
                'description' => 'Kelola data gugus tugas PAUD HI',
                'permissions' => json_encode(['gugus_tugas.*']),
            ],
            [
                'code' => 'mitra_paud',
                'name' => 'Mitra PAUD',
                'level' => 7,
                'description' => 'Kelola data mitra PAUD',
                'permissions' => json_encode(['mitra.*']),
            ],
            [
                'code' => 'forum_komunikasi',
                'name' => 'Forum Komunikasi PAUD',
                'level' => 8,
                'description' => 'Kelola forum komunikasi PAUD',
                'permissions' => json_encode(['forum.*']),
            ],
            [
                'code' => 'kelompok_kerja',
                'name' => 'Kelompok Kerja',
                'level' => 9,
                'description' => 'Kelola kegiatan kelompok kerja',
                'permissions' => json_encode(['pokja.*']),
            ],
            [
                'code' => 'juri_apresiasi',
                'name' => 'Juri Apresiasi',
                'level' => 10,
                'description' => 'Kelola penilaian apresiasi',
                'permissions' => json_encode(['apresiasi.*']),
            ],
            [
                'code' => 'kemendikdasmen',
                'name' => 'Kemendikdasmen',
                'level' => 11,
                'description' => 'Akses view only dan laporan',
                'permissions' => json_encode(['dashboard.view', 'reports.view']),
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                $role
            );
        }
    }
}
