<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Indicator;
use App\Models\IndicatorValue;
use App\Models\SyncLog;
use Throwable;

class IndicatorSyncService
{
    public function __construct(
        protected WorldBankService $worldBankService
    ) {}

    public function syncIndicatorForCountry(
        string $countryCode,
        string $indicatorCode,
        int $startYear = 2010,
        int $endYear = 2023
    ): array {
        $startedAt = now();

        $countryCode = strtoupper($countryCode);

        $country = Country::where('code', $countryCode)->first();
        $indicator = Indicator::where('code', $indicatorCode)->first();

        if (!$country) {
            return $this->registerError(
                indicator: null,
                countryCode: $countryCode,
                message: "El país con código {$countryCode} no existe en la base de datos.",
                startedAt: $startedAt
            );
        }

        if (!$indicator) {
            return $this->registerError(
                indicator: null,
                countryCode: $countryCode,
                message: "El indicador con código {$indicatorCode} no existe en la base de datos.",
                startedAt: $startedAt
            );
        }

        try {
            $result = $this->worldBankService->getIndicatorValues(
                countryCode: $countryCode,
                indicatorCode: $indicatorCode,
                startYear: $startYear,
                endYear: $endYear
            );

            if (!$result['success']) {
                return $this->registerError(
                    indicator: $indicator,
                    countryCode: $countryCode,
                    message: $result['message'] ?? 'Error desconocido al consultar la API.',
                    startedAt: $startedAt
                );
            }

            $recordsSynced = 0;

            foreach ($result['data'] as $row) {
                $year = isset($row['date']) ? (int) $row['date'] : null;
                $value = $row['value'] ?? null;

                if (!$year || $value === null) {
                    continue;
                }

                IndicatorValue::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'indicator_id' => $indicator->id,
                        'year' => $year,
                    ],
                    [
                        'value' => $value,
                    ]
                );

                $recordsSynced++;
            }

            SyncLog::create([
                'indicator_id' => $indicator->id,
                'country_code' => $countryCode,
                'status' => 'success',
                'records_synced' => $recordsSynced,
                'message' => "Sincronización completada para {$country->name} - {$indicator->name}.",
                'started_at' => $startedAt,
                'finished_at' => now(),
            ]);

            return [
                'success' => true,
                'country' => $country->name,
                'indicator' => $indicator->name,
                'records_synced' => $recordsSynced,
                'message' => "Datos sincronizados correctamente.",
            ];
        } catch (Throwable $exception) {
            return $this->registerError(
                indicator: $indicator,
                countryCode: $countryCode,
                message: $exception->getMessage(),
                startedAt: $startedAt
            );
        }
    }

    protected function registerError(
        ?Indicator $indicator,
        string $countryCode,
        string $message,
        $startedAt
    ): array {
        SyncLog::create([
            'indicator_id' => $indicator?->id,
            'country_code' => $countryCode,
            'status' => 'error',
            'records_synced' => 0,
            'message' => $message,
            'started_at' => $startedAt,
            'finished_at' => now(),
        ]);

        return [
            'success' => false,
            'country_code' => $countryCode,
            'indicator' => $indicator?->name,
            'records_synced' => 0,
            'message' => $message,
        ];
    }
}
