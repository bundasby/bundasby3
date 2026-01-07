<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Berita', 'slug' => 'berita', 'color' => 'teal', 'order' => 1],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan', 'color' => 'blue', 'order' => 2],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman', 'color' => 'amber', 'order' => 3],
            ['name' => 'Edukasi', 'slug' => 'edukasi', 'color' => 'green', 'order' => 4],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
