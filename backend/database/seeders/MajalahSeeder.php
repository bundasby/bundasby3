<?php

namespace Database\Seeders;

use App\Models\Majalah;
use Illuminate\Database\Seeder;

class MajalahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Majalah Bunda PAUD Edisi 1/2025',
                'description' => 'Edisi perdana majalah Bunda PAUD tahun 2025',
                'cover_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=600&fit=crop',
                'file_url' => '#',
                'edition' => 'Edisi 1',
                'publish_date' => '2025-01-15',
                'is_active' => true,
            ],
            [
                'title' => 'Majalah Bunda PAUD Edisi 2/2025',
                'description' => 'Edisi kedua majalah Bunda PAUD tahun 2025',
                'cover_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400&h=600&fit=crop',
                'file_url' => '#',
                'edition' => 'Edisi 2',
                'publish_date' => '2025-04-15',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Majalah::firstOrCreate(['title' => $item['title']], $item);
        }
    }
}
