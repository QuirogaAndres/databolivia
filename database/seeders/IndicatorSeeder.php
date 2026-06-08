<?php

namespace Database\Seeders;

use App\Models\Indicator;
use App\Models\IndicatorCategory;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
{
    public function run(): void
    {
        $indicators = [
            [
                'category' => 'poblacion',
                'code' => 'SP.POP.TOTL',
                'name' => 'Población total',
                'description' => 'Cantidad total de habitantes de un país.',
                'unit' => 'personas',
                'featured' => true,
            ],
            [
                'category' => 'economia',
                'code' => 'NY.GDP.MKTP.CD',
                'name' => 'PIB actual en USD',
                'description' => 'Producto Interno Bruto a precios actuales expresado en dólares estadounidenses.',
                'unit' => 'USD',
                'featured' => true,
            ],
            [
                'category' => 'economia',
                'code' => 'NY.GDP.PCAP.CD',
                'name' => 'PIB per cápita',
                'description' => 'Producto Interno Bruto dividido entre la población total.',
                'unit' => 'USD por persona',
                'featured' => true,
            ],
            [
                'category' => 'economia',
                'code' => 'FP.CPI.TOTL.ZG',
                'name' => 'Inflación anual',
                'description' => 'Variación porcentual anual del índice de precios al consumidor.',
                'unit' => '%',
                'featured' => true,
            ],
            [
                'category' => 'empleo',
                'code' => 'SL.UEM.TOTL.ZS',
                'name' => 'Desempleo total',
                'description' => 'Porcentaje de la fuerza laboral total que se encuentra desempleada.',
                'unit' => '% de la fuerza laboral',
                'featured' => true,
            ],
            [
                'category' => 'salud',
                'code' => 'SP.DYN.LE00.IN',
                'name' => 'Esperanza de vida al nacer',
                'description' => 'Número promedio de años que se espera que viva una persona al nacer.',
                'unit' => 'años',
                'featured' => true,
            ],
            [
                'category' => 'tecnologia',
                'code' => 'IT.NET.USER.ZS',
                'name' => 'Personas que usan internet',
                'description' => 'Porcentaje de la población que utiliza internet.',
                'unit' => '% de la población',
                'featured' => true,
            ],
            [
                'category' => 'energia',
                'code' => 'EG.ELC.ACCS.ZS',
                'name' => 'Acceso a electricidad',
                'description' => 'Porcentaje de la población con acceso a electricidad.',
                'unit' => '% de la población',
                'featured' => true,
            ],
            [
                'category' => 'medio-ambiente',
                'code' => 'EN.ATM.CO2E.PC',
                'name' => 'Emisiones de CO₂ per cápita',
                'description' => 'Emisiones de dióxido de carbono por persona.',
                'unit' => 'toneladas métricas per cápita',
                'featured' => false,
            ],
        ];

        foreach ($indicators as $item) {
            $category = IndicatorCategory::where('slug', $item['category'])->first();

            if (!$category) {
                continue;
            }

            Indicator::updateOrCreate(
                ['code' => $item['code']],
                [
                    'indicator_category_id' => $category->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'unit' => $item['unit'],
                    'active' => true,
                    'featured' => $item['featured'],
                ]
            );
        }
    }
}