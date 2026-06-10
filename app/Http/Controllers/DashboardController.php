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

        $selectedIndicator = Indicator::where('code', $selectedIndicatorCode)
            ->where('active', true)
            ->first();

        if (!$selectedIndicator) {
            $selectedIndicator = $featuredIndicators->first();
        }

        $availableYears = IndicatorValue::where('country_id', $country->id)
            ->select('year')
            ->distinct()
            ->orderBy('year')
            ->pluck('year');

        $minYear = (int) ($availableYears->min() ?? 2010);
        $maxYear = (int) ($availableYears->max() ?? now()->year);

        $startYear = (int) $request->query('start_year', $minYear);
        $endYear = (int) $request->query('end_year', $maxYear);

        if ($startYear > $endYear) {
            [$startYear, $endYear] = [$endYear, $startYear];
        }

        if ($availableYears->isNotEmpty()) {
            $startYear = max($startYear, $minYear);
            $endYear = min($endYear, $maxYear);
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

            $variation = $this->calculateVariation(
                $latestValue?->value,
                $previousValue?->value
            );

            return [
                'indicator' => $indicator,
                'latest_value' => $latestValue,
                'previous_value' => $previousValue,
                'variation' => $variation,
                'formatted_value' => $this->formatValue($latestValue?->value, $indicator->unit),
                'trend_label' => $this->getTrendLabel($variation),
                'trend_class' => $this->getTrendClass($variation),
            ];
        });

        $chartValues = collect();

        if ($selectedIndicator) {
            $chartValues = IndicatorValue::where('country_id', $country->id)
                ->where('indicator_id', $selectedIndicator->id)
                ->whereBetween('year', [$startYear, $endYear])
                ->orderBy('year')
                ->get();
        }

        $chartLabels = $chartValues->pluck('year')->values();
        $chartData = $chartValues->map(fn($item) => (float) $item->value)->values();

        $stats = $this->buildStats($chartValues, $selectedIndicator);

        return view('dashboard.index', [
            'country' => $country,
            'featuredIndicators' => $featuredIndicators,
            'selectedIndicator' => $selectedIndicator,
            'cards' => $cards,
            'chartValues' => $chartValues,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'availableYears' => $availableYears,
            'minYear' => $minYear,
            'maxYear' => $maxYear,
            'startYear' => $startYear,
            'endYear' => $endYear,
            'stats' => $stats,
        ]);
    }

    private function buildStats($values, ?Indicator $indicator): array
    {
        $validValues = $values->filter(fn($item) => $item->value !== null);

        if ($validValues->isEmpty() || !$indicator) {
            return [
                'records_count' => 0,
                'latest' => null,
                'previous' => null,
                'average' => null,
                'max' => null,
                'min' => null,
                'variation' => null,
                'latest_formatted' => 'Sin dato',
                'previous_formatted' => 'Sin dato',
                'average_formatted' => 'Sin dato',
                'max_formatted' => 'Sin dato',
                'min_formatted' => 'Sin dato',
                'variation_text' => 'Sin dato',
                'variation_class' => 'neutral',
            ];
        }

        $latest = $validValues->sortByDesc('year')->first();

        $previous = $validValues
            ->where('year', '<', $latest->year)
            ->sortByDesc('year')
            ->first();

        $average = $validValues->avg(fn($item) => (float) $item->value);

        $max = $validValues
            ->sortByDesc(fn($item) => (float) $item->value)
            ->first();

        $min = $validValues
            ->sortBy(fn($item) => (float) $item->value)
            ->first();

        $variation = $this->calculateVariation(
            $latest?->value,
            $previous?->value
        );

        return [
            'records_count' => $validValues->count(),
            'latest' => $latest,
            'previous' => $previous,
            'average' => $average,
            'max' => $max,
            'min' => $min,
            'variation' => $variation,
            'latest_formatted' => $this->formatValue($latest?->value, $indicator->unit),
            'previous_formatted' => $this->formatValue($previous?->value, $indicator->unit),
            'average_formatted' => $this->formatValue($average, $indicator->unit),
            'max_formatted' => $this->formatValue($max?->value, $indicator->unit),
            'min_formatted' => $this->formatValue($min?->value, $indicator->unit),
            'variation_text' => $variation === null
                ? 'Sin dato'
                : (($variation >= 0 ? '+' : '') . number_format($variation, 2, ',', '.') . '%'),
            'variation_class' => $this->getTrendClass($variation),
        ];
    }

    private function calculateVariation($latestValue, $previousValue): ?float
    {
        if ($latestValue === null || $previousValue === null) {
            return null;
        }

        $latest = (float) $latestValue;
        $previous = (float) $previousValue;

        if ($previous == 0.0) {
            return null;
        }

        return (($latest - $previous) / $previous) * 100;
    }

    private function getTrendLabel(?float $variation): string
    {
        if ($variation === null) {
            return 'Sin comparación';
        }

        if ($variation > 0) {
            return 'Subió';
        }

        if ($variation < 0) {
            return 'Bajó';
        }

        return 'Sin cambio';
    }

    private function getTrendClass(?float $variation): string
    {
        if ($variation === null || $variation == 0.0) {
            return 'neutral';
        }

        return $variation > 0 ? 'positive' : 'negative';
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
                return number_format($number / 1_000_000_000, 2, ',', '.') . ' mil millones USD';
            }

            if (abs($number) >= 1_000_000) {
                return number_format($number / 1_000_000, 2, ',', '.') . ' millones USD';
            }

            return number_format($number, 2, ',', '.') . ' USD';
        }

        if (str_contains($unit, 'personas')) {
            if (abs($number) >= 1_000_000) {
                return number_format($number / 1_000_000, 2, ',', '.') . ' millones';
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
