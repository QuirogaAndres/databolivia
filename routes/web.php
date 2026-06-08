<?php

use App\Models\Country;
use App\Models\Indicator;
use App\Models\IndicatorCategory;
use App\Services\WorldBankService;

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
