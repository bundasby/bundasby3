<?php

namespace Database\Seeders;

use App\Models\ApiClient;
use App\Models\ExternalApiConfig;
use App\Models\WebhookEndpoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ApiIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedExternalApiConfigs();
        $this->seedApiClients();
        $this->seedWebhookEndpoints();
    }

    /**
     * Seed external API configurations.
     */
    protected function seedExternalApiConfigs(): void
    {
        $configs = [
            [
                'name' => 'Dinas Pendidikan Kota Surabaya',
                'code' => 'disdik',
                'base_url' => 'https://api.disdik.surabaya.go.id',
                'auth_type' => 'api_key',
                'timeout' => 30,
                'description' => 'API untuk mengambil data sekolah PAUD dari Dinas Pendidikan',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kesehatan Kota Surabaya',
                'code' => 'dinkes',
                'base_url' => 'https://api.dinkes.surabaya.go.id',
                'auth_type' => 'bearer',
                'timeout' => 30,
                'description' => 'API untuk mengambil data kesehatan anak dan stunting',
                'is_active' => true,
            ],
            [
                'name' => 'Diskominfo Kota Surabaya',
                'code' => 'diskominfo',
                'base_url' => 'https://api.diskominfo.surabaya.go.id',
                'auth_type' => 'api_key',
                'timeout' => 30,
                'description' => 'API untuk mengambil data statistik kota',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kependudukan dan Catatan Sipil',
                'code' => 'dispendukcapil',
                'base_url' => 'https://api.dispendukcapil.surabaya.go.id',
                'auth_type' => 'basic',
                'timeout' => 60,
                'description' => 'API untuk verifikasi data kependudukan',
                'is_active' => false,
            ],
        ];

        foreach ($configs as $config) {
            ExternalApiConfig::updateOrCreate(
                ['code' => $config['code']],
                $config
            );
        }

        $this->command->info('External API configs seeded: ' . count($configs));
    }

    /**
     * Seed API clients (external dinas that access our API).
     */
    protected function seedApiClients(): void
    {
        // Demo/Test API client
        $credentials = ApiClient::generateCredentials();

        $demoClient = ApiClient::updateOrCreate(
            ['name' => 'Demo API Client'],
            [
                'organization' => 'Development & Testing',
                'api_key' => 'bp_demo_api_key_for_testing_only',
                'api_secret' => Hash::make('demo_secret_key_12345'),
                'allowed_endpoints' => ['*'],
                'rate_limit' => 1000,
                'contact_email' => 'developer@bundapaud.surabaya.go.id',
                'contact_person' => 'Developer',
                'description' => 'API client untuk development dan testing. Jangan gunakan di production!',
                'is_active' => true,
            ]
        );

        // Dinas Pendidikan client
        $disdikCredentials = ApiClient::generateCredentials();
        ApiClient::updateOrCreate(
            ['organization' => 'Dinas Pendidikan Kota Surabaya'],
            [
                'name' => 'Disdik Surabaya Integration',
                'api_key' => $disdikCredentials['api_key'],
                'api_secret' => $disdikCredentials['api_secret_hashed'],
                'allowed_endpoints' => ['data-paud', 'bunda-paud', 'statistics'],
                'rate_limit' => 500,
                'contact_email' => 'it@disdik.surabaya.go.id',
                'contact_person' => 'IT Disdik',
                'description' => 'Akses API untuk integrasi data PAUD dengan Dinas Pendidikan',
                'is_active' => true,
            ]
        );

        // Dinas Kesehatan client
        $dinkesCredentials = ApiClient::generateCredentials();
        ApiClient::updateOrCreate(
            ['organization' => 'Dinas Kesehatan Kota Surabaya'],
            [
                'name' => 'Dinkes Surabaya Integration',
                'api_key' => $dinkesCredentials['api_key'],
                'api_secret' => $dinkesCredentials['api_secret_hashed'],
                'allowed_endpoints' => ['data-paud', 'statistics'],
                'rate_limit' => 200,
                'contact_email' => 'it@dinkes.surabaya.go.id',
                'contact_person' => 'IT Dinkes',
                'description' => 'Akses API untuk data PAUD terkait program kesehatan anak',
                'is_active' => true,
            ]
        );

        $this->command->info('API clients seeded. Demo credentials:');
        $this->command->info('  API Key: bp_demo_api_key_for_testing_only');
        $this->command->info('  API Secret: demo_secret_key_12345');
    }

    /**
     * Seed sample webhook endpoints.
     */
    protected function seedWebhookEndpoints(): void
    {
        // Get demo client
        $demoClient = ApiClient::where('name', 'Demo API Client')->first();

        if ($demoClient) {
            WebhookEndpoint::updateOrCreate(
                [
                    'api_client_id' => $demoClient->id,
                    'name' => 'Demo Webhook Endpoint',
                ],
                [
                    'url' => 'https://webhook.site/your-unique-id', // Placeholder URL
                    'events' => ['article.created', 'data_paud.updated', 'complaint.new'],
                    'is_active' => false, // Disabled by default
                    'retry_count' => 3,
                    'timeout' => 30,
                    'description' => 'Demo webhook endpoint untuk testing. Ganti URL dengan URL asli.',
                ]
            );

            $this->command->info('Webhook endpoints seeded (disabled by default)');
        }
    }
}
