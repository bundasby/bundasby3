<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $token;
    protected string $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->token = config('services.whatsapp.token', '');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id', '');
    }

    /**
     * Send text message via WhatsApp Business API
     */
    public function sendMessage(string $to, string $message): array
    {
        try {
            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($to),
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent', [
                    'to' => $to,
                    'message_id' => $response->json('messages.0.id')
                ]);

                return [
                    'success' => true,
                    'message_id' => $response->json('messages.0.id')
                ];
            }

            Log::error('WhatsApp API error', [
                'to' => $to,
                'error' => $response->json()
            ]);

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Unknown error')
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send template message (for notifications)
     */
    public function sendTemplate(string $to, string $templateName, array $parameters = []): array
    {
        try {
            $components = [];

            if (!empty($parameters)) {
                $bodyParams = array_map(fn($value) => ['type' => 'text', 'text' => $value], $parameters);
                $components[] = [
                    'type' => 'body',
                    'parameters' => $bodyParams
                ];
            }

            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($to),
                    'type' => 'template',
                    'template' => [
                        'name' => $templateName,
                        'language' => ['code' => 'id'],
                        'components' => $components
                    ]
                ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Notify admin about new complaint
     */
    public function notifyNewComplaint(array $complaint): array
    {
        $adminPhones = config('services.whatsapp.admin_phones', []);

        $message = "🔔 *Pengaduan Baru*\n\n"
            . "📝 *Subjek:* {$complaint['subject']}\n"
            . "👤 *Nama:* {$complaint['name']}\n"
            . "📧 *Email:* {$complaint['email']}\n"
            . "📱 *Telepon:* {$complaint['phone']}\n"
            . "📅 *Waktu:* " . now()->format('d/m/Y H:i') . "\n\n"
            . "Silakan cek panel admin untuk detail lengkap.";

        $results = [];
        foreach ($adminPhones as $phone) {
            $results[$phone] = $this->sendMessage($phone, $message);
        }

        return $results;
    }

    /**
     * Notify admin about new consultation request
     */
    public function notifyNewConsultation(array $consultation): array
    {
        $adminPhones = config('services.whatsapp.admin_phones', []);

        $message = "📋 *Permintaan Konsultasi Baru*\n\n"
            . "👤 *Nama:* {$consultation['name']}\n"
            . "🏢 *Instansi:* {$consultation['organization']}\n"
            . "📧 *Email:* {$consultation['email']}\n"
            . "📱 *Telepon:* {$consultation['phone']}\n"
            . "📅 *Tanggal Request:* " . now()->format('d/m/Y H:i') . "\n\n"
            . "Silakan cek panel admin untuk konfirmasi.";

        $results = [];
        foreach ($adminPhones as $phone) {
            $results[$phone] = $this->sendMessage($phone, $message);
        }

        return $results;
    }

    /**
     * Notify admin about new building rental request
     */
    public function notifyNewPeminjaman(array $peminjaman): array
    {
        $adminPhones = config('services.whatsapp.admin_phones', []);

        $message = "🏛️ *Permintaan Peminjaman Gedung*\n\n"
            . "👤 *Nama:* {$peminjaman['name']}\n"
            . "🏢 *Instansi:* {$peminjaman['organization']}\n"
            . "📅 *Tanggal:* {$peminjaman['date']}\n"
            . "⏰ *Waktu:* {$peminjaman['time']}\n"
            . "📝 *Keperluan:* {$peminjaman['purpose']}\n\n"
            . "Silakan cek panel admin untuk konfirmasi.";

        $results = [];
        foreach ($adminPhones as $phone) {
            $results[$phone] = $this->sendMessage($phone, $message);
        }

        return $results;
    }

    /**
     * Send status update to user
     */
    public function sendStatusUpdate(string $phone, string $type, string $status, string $reference = ''): array
    {
        $statusLabels = [
            'pending' => '⏳ Menunggu',
            'processed' => '🔄 Sedang Diproses',
            'approved' => '✅ Disetujui',
            'rejected' => '❌ Ditolak',
            'resolved' => '✅ Selesai'
        ];

        $typeLabels = [
            'complaint' => 'Pengaduan',
            'consultation' => 'Konsultasi',
            'peminjaman' => 'Peminjaman Gedung',
            'mitra' => 'Pendaftaran Mitra'
        ];

        $statusLabel = $statusLabels[$status] ?? $status;
        $typeLabel = $typeLabels[$type] ?? $type;

        $message = "📢 *Update Status {$typeLabel}*\n\n"
            . ($reference ? "📌 *Referensi:* {$reference}\n" : "")
            . "📊 *Status:* {$statusLabel}\n"
            . "📅 *Waktu Update:* " . now()->format('d/m/Y H:i') . "\n\n"
            . "Terima kasih telah menggunakan layanan Bunda PAUD Kota Surabaya.";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send daily summary to admin
     */
    public function sendDailySummary(array $stats): array
    {
        $adminPhones = config('services.whatsapp.admin_phones', []);

        $message = "📊 *Ringkasan Harian - " . now()->format('d/m/Y') . "*\n\n"
            . "📰 Artikel Baru: {$stats['articles']}\n"
            . "📝 Pengaduan Masuk: {$stats['complaints']}\n"
            . "✅ Pengaduan Selesai: {$stats['resolved']}\n"
            . "📋 Konsultasi: {$stats['consultations']}\n"
            . "🏛️ Peminjaman: {$stats['peminjaman']}\n"
            . "🤝 Mitra Baru: {$stats['mitra']}\n\n"
            . "Selamat bekerja! 💪";

        $results = [];
        foreach ($adminPhones as $phone) {
            $results[$phone] = $this->sendMessage($phone, $message);
        }

        return $results;
    }

    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert Indonesian format to international
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Check if WhatsApp service is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->token) && !empty($this->phoneNumberId);
    }
}
