<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Path to the settings file
     */
    private string $settingsPath = 'settings.json';

    /**
     * Default settings
     */
    private array $defaultSettings = [
        'siteName' => 'Bunda PAUD Kota Surabaya',
        'email' => 'bundapaud@surabaya.go.id',
        'phone' => '(031) 5555-1234',
        'address' => 'Jl. Jimerto No.25-27, Ketabang, Kec. Genteng, Kota Surabaya, Jawa Timur 60272',
        'whatsappNumber' => '6281234567890',
        'whatsappMessage' => 'Halo, saya ingin bertanya tentang program Bunda PAUD Kota Surabaya',
        'hours' => 'Senin - Jumat: 08.00 - 16.00 WIB',
        'googleMapsUrl' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.5123456789!2d112.7315!3d-7.2900!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTcnMjQuMCJTIDExMsKwNDMnNTMuNCJF!5e0!3m2!1sid!2sid!4v1234567890',
    ];

    /**
     * Get current settings (Public)
     */
    public function index(): JsonResponse
    {
        $settings = $this->getSettings();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update settings (Admin only)
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'siteName' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:50',
            'address' => 'sometimes|string|max:500',
            'whatsappNumber' => 'sometimes|string|max:20',
            'whatsappMessage' => 'sometimes|string|max:500',
            'hours' => 'sometimes|string|max:100',
            'googleMapsUrl' => 'sometimes|string|max:2000',
        ]);

        // Get current settings and merge with new values
        $currentSettings = $this->getSettings();
        $newSettings = array_merge($currentSettings, $validated);

        // Save to file
        $this->saveSettings($newSettings);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan',
            'data' => $newSettings,
        ]);
    }

    /**
     * Get settings from file or return defaults
     */
    private function getSettings(): array
    {
        $filePath = storage_path($this->settingsPath);

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $settings = json_decode($content, true);

            if (is_array($settings)) {
                return array_merge($this->defaultSettings, $settings);
            }
        }

        return $this->defaultSettings;
    }

    /**
     * Save settings to file
     */
    private function saveSettings(array $settings): void
    {
        $filePath = storage_path($this->settingsPath);
        file_put_contents($filePath, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
