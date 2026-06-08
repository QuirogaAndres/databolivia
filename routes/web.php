<?php

use App\Models\Country;
use App\Models\Indicator;
use App\Models\IndicatorCategory;

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
