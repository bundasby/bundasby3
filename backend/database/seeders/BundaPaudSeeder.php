<?php

namespace Database\Seeders;

use App\Models\BundaPaud;
use Illuminate\Database\Seeder;

class BundaPaudSeeder extends Seeder
{
    public function run(): void
    {
        // Data Bunda PAUD Kota (stored as kecamatan type since enum only supports kecamatan/kelurahan)
        $bundaKota = [
            'name' => 'Hj. Rini Indriyani, S.Pd., M.M.',
            'position' => 'Bunda PAUD Kota Surabaya',
            'area' => 'Kota Surabaya',
            'type' => 'kecamatan', // Using kecamatan since enum doesn't have 'kota'
            'phone' => '031-5472550',
            'email' => 'bundapaud@surabaya.go.id',
            'photo_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop',
            'kecamatan_id' => null,
            'is_active' => true,
        ];

        BundaPaud::firstOrCreate(['name' => $bundaKota['name']], $bundaKota);

        // Data Bunda PAUD Kecamatan (31 Kecamatan di Surabaya)
        $kecamatanList = [
            ['kecamatan' => 'Tegalsari', 'nama' => 'Hj. Sri Wahyuni, S.Pd.'],
            ['kecamatan' => 'Genteng', 'nama' => 'Dra. Siti Aminah'],
            ['kecamatan' => 'Bubutan', 'nama' => 'Hj. Nurul Hidayah, S.Pd.'],
            ['kecamatan' => 'Simokerto', 'nama' => 'Dra. Endang Sulistyowati'],
            ['kecamatan' => 'Pabean Cantian', 'nama' => 'Hj. Yuliana, M.Pd.'],
            ['kecamatan' => 'Semampir', 'nama' => 'Siti Fatimah, S.Pd.'],
            ['kecamatan' => 'Krembangan', 'nama' => 'Dra. Hj. Kartini'],
            ['kecamatan' => 'Kenjeran', 'nama' => 'Sri Mulyani, S.Pd.'],
            ['kecamatan' => 'Bulak', 'nama' => 'Hj. Umi Kulsum, M.Pd.'],
            ['kecamatan' => 'Tambaksari', 'nama' => 'Dra. Ratna Dewi'],
            ['kecamatan' => 'Gubeng', 'nama' => 'Hj. Siska Permata, S.Pd.'],
            ['kecamatan' => 'Rungkut', 'nama' => 'Dr. Hj. Indah Sari, M.Pd.'],
            ['kecamatan' => 'Tenggilis Mejoyo', 'nama' => 'Hj. Dewi Sartika, S.Pd.'],
            ['kecamatan' => 'Gunung Anyar', 'nama' => 'Sri Lestari, M.Pd.'],
            ['kecamatan' => 'Sukolilo', 'nama' => 'Hj. Nur Aini, S.Pd.'],
            ['kecamatan' => 'Mulyorejo', 'nama' => 'Dra. Hj. Wulandari'],
            ['kecamatan' => 'Sawahan', 'nama' => 'Hj. Tri Handayani, M.Pd.'],
            ['kecamatan' => 'Wonokromo', 'nama' => 'Sri Rejeki, S.Pd.'],
            ['kecamatan' => 'Karangpilang', 'nama' => 'Hj. Sulistyorini, M.Pd.'],
            ['kecamatan' => 'Dukuh Pakis', 'nama' => 'Dra. Hj. Yulianti'],
            ['kecamatan' => 'Wiyung', 'nama' => 'Hj. Rahayu, S.Pd.'],
            ['kecamatan' => 'Wonocolo', 'nama' => 'Sri Hartini, M.Pd.'],
            ['kecamatan' => 'Gayungan', 'nama' => 'Hj. Megawati, S.Pd.'],
            ['kecamatan' => 'Jambangan', 'nama' => 'Dra. Hj. Sumiati'],
            ['kecamatan' => 'Tandes', 'nama' => 'Hj. Kusumawati, M.Pd.'],
            ['kecamatan' => 'Sukomanunggal', 'nama' => 'Sri Puji Rahayu, S.Pd.'],
            ['kecamatan' => 'Asemrowo', 'nama' => 'Hj. Nur Hidayah, S.Pd.'],
            ['kecamatan' => 'Benowo', 'nama' => 'Dra. Hj. Siti Rohmah'],
            ['kecamatan' => 'Pakal', 'nama' => 'Hj. Erni Susanti, M.Pd.'],
            ['kecamatan' => 'Lakarsantri', 'nama' => 'Sri Widayati, S.Pd.'],
            ['kecamatan' => 'Sambikerep', 'nama' => 'Hj. Dwi Ratnasari, M.Pd.'],
        ];

        foreach ($kecamatanList as $index => $data) {
            BundaPaud::firstOrCreate(
                ['name' => $data['nama']],
                [
                    'name' => $data['nama'],
                    'position' => 'Bunda PAUD Kecamatan ' . $data['kecamatan'],
                    'area' => $data['kecamatan'],
                    'type' => 'kecamatan',
                    'phone' => '031-' . rand(5000000, 5999999),
                    'email' => 'bundapaud.' . strtolower(str_replace(' ', '', $data['kecamatan'])) . '@surabaya.go.id',
                    'photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode($data['nama']) . '&background=2563eb&color=fff&size=200',
                    'kecamatan_id' => $index + 1,
                    'is_active' => true,
                ]
            );
        }

        // Data Bunda PAUD Kelurahan (sample - 5 per kecamatan untuk beberapa kecamatan)
        $kelurahanSample = [
            ['kecamatan' => 'Tegalsari', 'kelurahan' => 'Kedungdoro', 'nama' => 'Ibu Siti Maryam'],
            ['kecamatan' => 'Tegalsari', 'kelurahan' => 'Keputran', 'nama' => 'Ibu Hartati'],
            ['kecamatan' => 'Tegalsari', 'kelurahan' => 'Dr. Soetomo', 'nama' => 'Ibu Lestari'],
            ['kecamatan' => 'Genteng', 'kelurahan' => 'Genteng', 'nama' => 'Ibu Wahyuni'],
            ['kecamatan' => 'Genteng', 'kelurahan' => 'Embong Kaliasin', 'nama' => 'Ibu Sunarti'],
            ['kecamatan' => 'Genteng', 'kelurahan' => 'Kapasari', 'nama' => 'Ibu Nurhayati'],
            ['kecamatan' => 'Rungkut', 'kelurahan' => 'Rungkut Kidul', 'nama' => 'Ibu Endang'],
            ['kecamatan' => 'Rungkut', 'kelurahan' => 'Kalirungkut', 'nama' => 'Ibu Suparmi'],
            ['kecamatan' => 'Rungkut', 'kelurahan' => 'Medokan Ayu', 'nama' => 'Ibu Warsiti'],
            ['kecamatan' => 'Sukolilo', 'kelurahan' => 'Keputih', 'nama' => 'Ibu Sulastri'],
            ['kecamatan' => 'Sukolilo', 'kelurahan' => 'Gebang Putih', 'nama' => 'Ibu Mujiati'],
            ['kecamatan' => 'Sukolilo', 'kelurahan' => 'Klampis Ngasem', 'nama' => 'Ibu Sumiyati'],
        ];

        foreach ($kelurahanSample as $data) {
            BundaPaud::firstOrCreate(
                ['name' => $data['nama']],
                [
                    'name' => $data['nama'],
                    'position' => 'Bunda PAUD Kelurahan ' . $data['kelurahan'],
                    'area' => $data['kelurahan'],
                    'type' => 'kelurahan',
                    'phone' => '08' . rand(1100000000, 9999999999),
                    'email' => 'bundapaud.' . strtolower(str_replace(' ', '', $data['kelurahan'])) . '@surabaya.go.id',
                    'photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode($data['nama']) . '&background=10b981&color=fff&size=200',
                    'kecamatan_id' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
