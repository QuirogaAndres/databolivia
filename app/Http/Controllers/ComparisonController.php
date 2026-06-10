<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Indicator;
use App\Models\IndicatorValue;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::where('active', true)
            ->orderBy('name')
            ->get();

        $indicators = Indicator::where('active', true)
            ->where('featured', true)
            ->orderBy('name')
            ->get();

        $selectedCountryCodes = $request->query('countries', ['BO', 'PE', 'CL']);

        if (!is_array($selectedCountryCodes)) {
            $selectedCountryCodes = [$selectedCountryCodes];
        }

        $selectedCountryCodes = collect($selectedCountryCodes)
            ->filter()
            ->map(fn($code) => strtoupper((string) $code))
            ->unique()
            ->take(6)
            ->values()
            ->all();

        if (empty($selectedCountryCodes)) {
            $selectedCountryCodes = ['BO', 'PE', 'CL'];
        }

        $selectedCountries = Country::whereIn('code', $selectedCountryCodes)
            ->get()
            ->sortBy(fn($country) => array_search($country->code, $selectedCountryCodes))
            ->values();

        $selectedIndicatorCode = $request->query('indicator', 'NY.GDP.PCAP.CD');

        $selectedIndicator = Indicator::where('code', $selectedIndicatorCode)
            ->where('active', true)
            ->first();

        if (!$selectedIndicator) {
            $selectedIndicator = $indicators->first();
        }

        $availableYears = collect();

        if ($selectedIndicator && $selectedCountries->isNotEmpty()) {
            $availableYears = IndicatorValue::whereIn('country_id', $selectedCountries->pluck('id'))
                ->where('indicator_id', $selectedIndicator->id)
                ->select('year')
                ->distinct()
                ->orderBy('year')
                ->pluck('year');
        }

        $minYear = (int) ($availableYears->min() ?? 2010);
        $maxYear = (int) ($availableYears->max() ?? 2023);

        $startYear = (int) $request->query('start_year', $minYear);
        $endYear = (int) $request->query('end_year', $maxYear);

        if ($startYear > $endYear) {
            [$startYear, $endYear] = [$endYear, $startYear];
        }

        $startYear = max($startYear, $minYear);
        $endYear = min($endYear, $maxYear);

        $labels = collect(range($startYear, $endYear));

        $values = collect();

        if ($selectedIndicator && $selectedCountries->isNotEmpty()) {
            $values = IndicatorValue::with(['country', 'indicator'])
                ->whereIn('country_id', $selectedCountries->pluck('id'))
                ->where('indicator_id', $selectedIndicator->id)
                ->whereBetween('year', [$startYear, $endYear])
                ->orderBy('year')
                ->get();
        }

        $valuesByCountry = $values->groupBy(fn($item) => $item->country->code);

        $datasets = $selectedCountries->map(function ($country) use ($valuesByCountry, $labels) {
            $countryValues = $valuesByCountry->get($country->code, collect())
                ->keyBy('year');

            return [
                'label' => $country->name,
                'data' => $labels->map(function ($year) use ($countryValues) {
                    $record = $countryValues->get($year);

                    return $record && $record->value !== null
                        ? (float) $record->value
                        : null;
                })->values(),
                'borderWidth' => 3,
                'tension' => 0.35,
                'fill' => false,
                'spanGaps' => true,
            ];
        })->values();

        $countrySummaries = $selectedCountries->map(function ($country) use ($valuesByCountry, $selectedIndicator) {
            $items = $valuesByCountry->get($country->code, collect())
                ->filter(fn($item) => $item->value !== null)
                ->sortByDesc('year')
                ->values();

            $latest = $items->first();
            $previous = $items->where('year', '<', $latest?->year)->first();

            $variation = $this->calculateVariation(
                $latest?->value,
                $previous?->value
            );

            return [
                'country' => $country,
                'latest' => $latest,
                'previous' => $previous,
                'variation' => $variation,
                'variation_text' => $variation === null
                    ? 'Sin dato'
                    : (($variation >= 0 ? '+' : '') . number_format($variation, 2, ',', '.') . '%'),
                'variation_class' => $this->getTrendClass($variation),
                'latest_formatted' => $this->formatValue($latest?->value, $selectedIndicator?->unit),
            ];
        });

        $ranking = $countrySummaries
            ->filter(fn($item) => $item['latest'] !== null)
            ->sortByDesc(fn($item) => (float) $item['latest']->value)
            ->values();

        $tableRows = $labels->reverse()->map(function ($year) use ($selectedCountries, $valuesByCountry, $selectedIndicator) {
            $countriesData = $selectedCountries->map(function ($country) use ($valuesByCountry, $year, $selectedIndicator) {
                $record = $valuesByCountry
                    ->get($country->code, collect())
                    ->firstWhere('year', $year);

                return [
                    'country' => $country,
                    'value' => $record?->value,
                    'formatted_value' => $this->formatValue($record?->value, $selectedIndicator?->unit),
                ];
            });

            return [
                'year' => $year,
                'countries' => $countriesData,
            ];
        });

        return view('comparison.index', [
            'countries' => $countries,
            'indicators' => $indicators,
            'selectedCountries' => $selectedCountries,
            'selectedCountryCodes' => $selectedCountryCodes,
            'selectedIndicator' => $selectedIndicator,
            'availableYears' => $availableYears,
            'minYear' => $minYear,
            'maxYear' => $maxYear,
            'startYear' => $startYear,
            'endYear' => $endYear,
            'labels' => $labels,
            'datasets' => $datasets,
            'countrySummaries' => $countrySummaries,
            'ranking' => $ranking,
            'tableRows' => $tableRows,
        ]);
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
