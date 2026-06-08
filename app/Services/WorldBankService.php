<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WorldBankService
{
    protected string $baseUrl = 'https://api.worldbank.org/v2';

    public function getIndicatorValues(
        string $countryCode,
        string $indicatorCode,
        int $startYear = 2010,
        int $endYear = 2023
    ): array {
        $url = "{$this->baseUrl}/country/{$countryCode}/indicator/{$indicatorCode}";

        $response = Http::timeout(20)->get($url, [
            'format' => 'json',
            'date' => "{$startYear}:{$endYear}",
            'per_page' => 100,
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'No se pudo consultar la API del Banco Mundial.',
                'status' => $response->status(),
                'data' => [],
            ];
        }

        $json = $response->json();

        if (!is_array($json) || count($json) < 2) {
            return [
                'success' => false,
                'message' => 'La API respondió, pero el formato no es el esperado.',
                'status' => $response->status(),
                'data' => [],
            ];
        }

        return [
            'success' => true,
            'message' => 'Datos obtenidos correctamente.',
            'status' => $response->status(),
            'metadata' => $json[0] ?? [],
            'data' => $json[1] ?? [],
        ];
    }
}
