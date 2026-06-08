<?php

namespace Database\Seeders;

use App\Models\IndicatorCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IndicatorCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Población',
                'description' => 'Indicadores relacionados con población, demografía y crecimiento poblacional.',
            ],
            [
                'name' => 'Economía',
                'description' => 'Indicadores económicos como PIB, PIB per cápita e inflación.',
            ],
            [
                'name' => 'Empleo',
                'description' => 'Indicadores relacionados con mercado laboral y desempleo.',
            ],
            [
                'name' => 'Salud',
                'description' => 'Indicadores relacionados con salud y esperanza de vida.',
            ],
            [
                'name' => 'Tecnología',
                'description' => 'Indicadores relacionados con acceso a internet y uso de tecnología.',
            ],
            [
                'name' => 'Energía',
                'description' => 'Indicadores relacionados con acceso a electricidad y recursos energéticos.',
            ],
            [
                'name' => 'Medio ambiente',
                'description' => 'Indicadores relacionados con emisiones y sostenibilidad ambiental.',
            ],
        ];

        foreach ($categories as $category) {
            IndicatorCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'active' => true,
                ]
            );
        }
    }
}