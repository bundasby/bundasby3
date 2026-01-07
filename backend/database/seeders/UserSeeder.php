<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $superAdminRole = Role::where('code', 'super_admin')->first();
        $adminRole = Role::where('code', 'admin')->first();
        $bundaKotaRole = Role::where('code', 'bunda_kota')->first();
        $bundaKecamatanRole = Role::where('code', 'bunda_kecamatan')->first();
        $bundaKelurahanRole = Role::where('code', 'bunda_kelurahan')->first();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@bundapaud.surabaya.go.id',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Admin Bunda PAUD',
                'email' => 'admin@bundapaud.surabaya.go.id',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Bunda PAUD Kota Surabaya',
                'email' => 'bundakota@bundapaud.surabaya.go.id',
                'password' => Hash::make('password123'),
                'role_id' => $bundaKotaRole?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Bunda PAUD Kec. Tegalsari',
                'email' => 'tegalsari@bundapaud.surabaya.go.id',
                'password' => Hash::make('password123'),
                'role_id' => $bundaKecamatanRole?->id,
                'kecamatan_id' => 'tegalsari',
                'is_active' => true,
            ],
            [
                'name' => 'Bunda PAUD Kel. Wonorejo',
                'email' => 'wonorejo@bundapaud.surabaya.go.id',
                'password' => Hash::make('password123'),
                'role_id' => $bundaKelurahanRole?->id,
                'kecamatan_id' => 'tegalsari',
                'kelurahan_id' => 'wonorejo',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
