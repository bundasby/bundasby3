<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = [
            [
                'section_key' => 'visi_misi',
                'title' => 'Visi dan Misi',
                'content' => '<h3>Visi</h3><p>Mewujudkan Generasi Emas Surabaya yang Sehat, Cerdas, Ceria, dan Berakhlak Mulia.</p><h3>Misi</h3><ol><li>Meningkatkan kualitas layanan PAUD HI yang holistik dan integratif.</li><li>Mendorong partisipasi masyarakat dalam pembinaan PAUD.</li><li>Mewujudkan kesiapan anak masuk sekolah dasar.</li></ol>',
                'image' => null,
            ],
            [
                'section_key' => 'sambutan',
                'title' => 'Sambutan Bunda PAUD',
                'content' => '<p>Assalamu’alaikum Warahmatullahi Wabarakatuh,</p><p>Selamat datang di website resmi Bunda PAUD Kota Surabaya. Website ini kami hadirkan sebagai sarana informasi dan komunikasi bagi seluruh elemen masyarakat, khususnya para pendidik, orang tua, dan pemerhati pendidikan anak usia dini.</p><p>Mari kita bersinergi demi masa depan anak-anak Surabaya.</p><p>Wassalamu’alaikum Warahmatullahi Wabarakatuh.</p>',
                'image' => 'https://placehold.co/400x500/007bff/ffffff?text=Foto+Bunda+PAUD',
            ],
            [
                'section_key' => 'arti_logo',
                'title' => 'Arti Logo Bunda PAUD',
                'content' => '<p>Logo Bunda PAUD melambangkan kasih sayang, perlindungan, dan pendidikan yang berpusat pada anak.</p><ul><li><strong>Warna Biru:</strong> Melambangkan kedalaman ilmu dan ketenangan.</li><li><strong>Warna Kuning:</strong> Melambangkan keceriaan dan masa depan cerah.</li><li><strong>Buku Terbuka:</strong> Simbol sumber pengetahuan.</li></ul>',
                'image' => 'https://placehold.co/300x300/eab308/ffffff?text=Logo+Bunda+PAUD',
            ],
            [
                'section_key' => 'tugas_fungsi',
                'title' => 'Tugas dan Fungsi',
                'content' => '<p>Bunda PAUD memiliki peran strategis dalam pengembangan SDM sejak dini.</p><h4>Tugas Pokok:</h4><ul><li>Membangun komunikasi dengan semua elemen masyarakat untuk pengembangan PAUD.</li><li>Mendorong Angka Partisipasi Kasar (APK) PAUD.</li></ul><h4>Fungsi:</h4><ul><li>Sebagai simbol dan tokoh sentral gerakan nasional PAUD di wilayahnya.</li><li>Sebagai motivator bagi masyarakat dalam pembinaan PAUD.</li></ul>',
                'image' => null,
            ],
            [
                'section_key' => 'struktur_organisasi',
                'title' => 'Struktur Organisasi',
                'content' => '<p>Struktur organisasi Pokja Bunda PAUD Kota Surabaya terdiri dari:</p><ul><li><strong>Pembina:</strong> Walikota Surabaya</li><li><strong>Ketua:</strong> Bunda PAUD Kota Surabaya</li><li><strong>Wakil Ketua:</strong> Istri Wakil Walikota</li><li><strong>Sekretaris:</strong> Kepala Dinas Pendidikan</li></ul>',
                'image' => 'https://placehold.co/800x400/10b981/ffffff?text=Bagan+Struktur',
            ],
        ];

        foreach ($profiles as $profile) {
            Profile::updateOrCreate(
                ['section_key' => $profile['section_key']],
                $profile
            );
        }
    }
}
