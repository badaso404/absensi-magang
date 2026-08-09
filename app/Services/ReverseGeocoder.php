<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Mengubah koordinat GPS menjadi nama lokasi yang terbaca manusia.
 *
 * Absensi tidak boleh gagal hanya karena layanan geocoding mati, jadi setiap
 * kegagalan jatuh kembali ke koordinat mentah.
 */
class ReverseGeocoder
{
    public function resolve(float|string|null $latitude, float|string|null $longitude): ?string
    {
        if (!$this->koordinatValid($latitude, $longitude)) {
            return null;
        }

        $fallback = sprintf('Lat: %s, Lon: %s', $latitude, $longitude);
        $apiKey = config('magang.geoapify.key');

        if (!$apiKey) {
            return $fallback;
        }

        try {
            $response = Http::timeout(config('magang.geoapify.timeout'))
                ->get('https://api.geoapify.com/v1/geocode/reverse', [
                    'lat'    => $latitude,
                    'lon'    => $longitude,
                    'apiKey' => $apiKey,
                    'lang'   => 'id',
                ]);

            if ($response->successful()) {
                return $response->json('features.0.properties.formatted') ?: $fallback;
            }

            Log::warning('Reverse geocoding gagal', ['status' => $response->status()]);
        } catch (\Throwable $e) {
            Log::warning('Reverse geocoding error: ' . $e->getMessage());
        }

        return $fallback;
    }

    /**
     * Koordinat 0,0 dianggap tidak valid — itu nilai default browser saat GPS ditolak.
     */
    private function koordinatValid(float|string|null $latitude, float|string|null $longitude): bool
    {
        if ($latitude === null || $longitude === null || $latitude === '' || $longitude === '') {
            return false;
        }

        return (float) $latitude !== 0.0 || (float) $longitude !== 0.0;
    }
}
