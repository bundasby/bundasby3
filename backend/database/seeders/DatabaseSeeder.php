<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            ArticleSeeder::class,
            GallerySeeder::class,
            DocumentSeeder::class,
            SliderSeeder::class,
            ProfileSeeder::class,
            ProgramSeeder::class,
            DataPaudSeeder::class,
                // New Content Seeders
            CategorySeeder::class,
            InfografisSeeder::class,
            MateriSeeder::class,
            MajalahSeeder::class,
            BundaTvSeeder::class,
            PeraturanSeeder::class,
            NpkSeeder::class,
            LaporanSeeder::class,
            GugusTugasSeeder::class,
                // Phase 2 Content Seeders
            BundaPaudSeeder::class,
            MitraPaudSeeder::class,
        ]);
    }
}
