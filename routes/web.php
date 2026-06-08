<?php

use App\Models\Country;
use App\Models\Indicator;
use App\Models\IndicatorCategory;
use App\Services\WorldBankService;
use App\Models\IndicatorValue;
use App\Models\SyncLog;

Route::get('/debug/database', function () {
    return response()->json([
        'countries' => Country::count(),
        'categories' => IndicatorCategory::count(),
        'indicators' => Indicator::count(),
        'bolivia' => Country::where('code', 'BO')->first(),
        'featured_indicators' => Indicator::where('featured', true)
            ->select('code', 'name', 'unit')
            ->get(),
    ]);
});

Route::get('/debug/worldbank', function (WorldBankService $worldBankService) {
    $result = $worldBankService->getIndicatorValues(
        countryCode: 'BO',
        indicatorCode: 'SP.POP.TOTL',
        startYear: 2018,
        endYear: 2023
    );

    return response()->json($result);
});

Route::get('/debug/values', function () {
    return response()->json([
        'total_values' => IndicatorValue::count(),
        'latest_values' => IndicatorValue::with(['country', 'indicator'])
            ->orderByDesc('year')
            ->limit(10)
            ->get()
            ->map(function ($value) {
                return [
                    'country' => $value->country->name,
                    'indicator' => $value->indicator->name,
                    'indicator_code' => $value->indicator->code,
                    'year' => $value->year,
                    'value' => $value->value,
                    'unit' => $value->indicator->unit,
                ];
            }),
        'sync_logs' => SyncLog::with('indicator')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'indicator' => $log->indicator?->name,
                    'country_code' => $log->country_code,
                    'status' => $log->status,
                    'records_synced' => $log->records_synced,
                    'message' => $log->message,
                    'started_at' => $log->started_at,
                    'finished_at' => $log->finished_at,
                ];
            }),
    ]);
});


Route::get('/debug/bolivia-summary', function () {
    $values = IndicatorValue::with(['country', 'indicator'])
        ->whereHas('country', function ($query) {
            $query->where('code', 'BO');
        })
        ->orderBy('indicator_id')
        ->orderByDesc('year')
        ->get()
        ->groupBy('indicator.name')
        ->map(function ($items, $indicatorName) {
            $latest = $items->first();

            return [
                'indicator' => $indicatorName,
                'code' => $latest->indicator->code,
                'unit' => $latest->indicator->unit,
                'latest_year' => $latest->year,
                'latest_value' => $latest->value,
                'records' => $items->count(),
            ];
        })
        ->values();

    return response()->json([
        'country' => 'Bolivia',
        'summary' => $values,
    ]);
});

