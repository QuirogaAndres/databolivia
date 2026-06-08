<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            [
                'code' => 'BO',
                'iso2_code' => 'BO',
                'name' => 'Bolivia',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Lower middle income',
            ],
            [
                'code' => 'PE',
                'iso2_code' => 'PE',
                'name' => 'Perú',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Upper middle income',
            ],
            [
                'code' => 'CL',
                'iso2_code' => 'CL',
                'name' => 'Chile',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'High income',
            ],
            [
                'code' => 'AR',
                'iso2_code' => 'AR',
                'name' => 'Argentina',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Upper middle income',
            ],
            [
                'code' => 'BR',
                'iso2_code' => 'BR',
                'name' => 'Brasil',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Upper middle income',
            ],
            [
                'code' => 'CO',
                'iso2_code' => 'CO',
                'name' => 'Colombia',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Upper middle income',
            ],
            [
                'code' => 'EC',
                'iso2_code' => 'EC',
                'name' => 'Ecuador',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Upper middle income',
            ],
            [
                'code' => 'PY',
                'iso2_code' => 'PY',
                'name' => 'Paraguay',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Upper middle income',
            ],
            [
                'code' => 'UY',
                'iso2_code' => 'UY',
                'name' => 'Uruguay',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'High income',
            ],
            [
                'code' => 'MX',
                'iso2_code' => 'MX',
                'name' => 'México',
                'region' => 'Latin America & Caribbean',
                'income_level' => 'Upper middle income',
            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                array_merge($country, ['active' => true])
            );
        }
    }
}