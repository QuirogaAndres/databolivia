<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Indicator;
use App\Models\IndicatorValue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $country = Country::where('code', 'BO')->firstOrFail();

        $featuredIndicators = Indicator::with('category')
            ->where('active', true)
            ->where('featured', true)
            ->orderBy('name')
            ->get();

        $selectedIndicatorCode = $request->query(
            'indicator',
            $featuredIndicators->first()?->code
        );

        $selectedIndicator = Indicator::where('code', $selectedIndicatorCode)->first();

        if (!$selectedIndicator) {
            $selectedIndicator = $featuredIndicators->first();
        }

        $cards = $featuredIndicators->map(function ($indicator) use ($country) {
            $latestValue = IndicatorValue::where('country_id', $country->id)
                ->where('indicator_id', $indicator->id)
                ->orderByDesc('year')
                ->first();

            $previousValue = null;

            if ($latestValue) {
                $previousValue = IndicatorValue::where('country_id', $country->id)
                    ->where('indicator_id', $indicator->id)
                    ->where('year', '<', $latestValue->year)
                    ->orderByDesc('year')
                    ->first();
            }

            $variation = null;

            if (
                $latestValue &&
                $previousValue &&
                (float) $previousValue->value !== 0.0
            ) {
                $variation = (
                    ((float) $latestValue->value - (float) $previousValue->value)
                    / (float) $previousValue->value
                ) * 100;
            }

            return [
                'indicator' => $indicator,
                'latest_value' => $latestValue,
                'previous_value' => $previousValue,
                'variation' => $variation,
                'formatted_value' => $this->formatValue(
                    $latestValue?->value,
                    $indicator->unit
                ),
            ];
        });

        $chartValues = collect();

        if ($selectedIndicator) {
            $chartValues = IndicatorValue::where('country_id', $country->id)
                ->where('indicator_id', $selectedIndicator->id)
                ->orderBy('year')
                ->get();
        }

        $chartLabels = $chartValues->pluck('year')->values();
        $chartData = $chartValues->map(fn($item) => (float) $item->value)->values();

        return view('dashboard.index', [
            'country' => $country,
            'featuredIndicators' => $featuredIndicators,
            'selectedIndicator' => $selectedIndicator,
            'cards' => $cards,
            'chartValues' => $chartValues,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }

    private function formatValue($value, ?string $unit): string
    {
        if ($value === null) {
            return 'Sin dato';
        }

        $number = (float) $value;
        $unit = $unit ?? '';

        if (str_contains($unit, 'USD')) {
            if (abs($number) >= 1_000_000_000) {
                return number_format($number / 1_000_000_000, 2, ',', '.') . ' mil M USD';
            }

            if (abs($number) >= 1_000_000) {
                return number_format($number / 1_000_000, 2, ',', '.') . ' M USD';
            }

            return number_format($number, 2, ',', '.') . ' USD';
        }

        if (str_contains($unit, 'personas')) {
            if (abs($number) >= 1_000_000) {
                return number_format($number / 1_000_000, 2, ',', '.') . ' M';
            }

            return number_format($number, 0, ',', '.') . ' personas';
        }

        if (str_contains($unit, '%')) {
            return number_format($number, 2, ',', '.') . ' %';
        }

        if (str_contains($unit, 'años')) {
            return number_format($number, 2, ',', '.') . ' años';
        }

        return number_format($number, 2, ',', '.') . ' ' . $unit;
    }
}
