<?php

namespace App\Console\Commands;

use App\Models\Indicator;
use App\Services\IndicatorSyncService;
use Illuminate\Console\Command;

class SyncWorldBankData extends Command
{
    protected $signature = 'databolivia:sync
                            {--country=BO : Código del país a sincronizar}
                            {--indicator= : Código específico del indicador}
                            {--start=2010 : Año inicial}
                            {--end=2023 : Año final}';

    protected $description = 'Sincroniza indicadores socioeconómicos desde World Bank API hacia la base de datos local.';

    public function handle(IndicatorSyncService $indicatorSyncService): int
    {
        $countryCode = strtoupper((string) $this->option('country'));
        $indicatorCode = $this->option('indicator');
        $startYear = (int) $this->option('start');
        $endYear = (int) $this->option('end');

        $this->info("Iniciando sincronización de DataBolivia...");
        $this->line("País: {$countryCode}");
        $this->line("Periodo: {$startYear} - {$endYear}");

        $indicatorsQuery = Indicator::query()
            ->where('active', true)
            ->orderBy('code');

        if ($indicatorCode) {
            $indicatorsQuery->where('code', $indicatorCode);
        }

        $indicators = $indicatorsQuery->get();

        if ($indicators->isEmpty()) {
            $this->error('No se encontraron indicadores activos para sincronizar.');
            return Command::FAILURE;
        }

        $this->line("Indicadores a sincronizar: {$indicators->count()}");
        $this->newLine();

        $successCount = 0;
        $errorCount = 0;
        $totalRecords = 0;

        foreach ($indicators as $indicator) {
            $this->line("Sincronizando {$indicator->code} - {$indicator->name}...");

            $result = $indicatorSyncService->syncIndicatorForCountry(
                countryCode: $countryCode,
                indicatorCode: $indicator->code,
                startYear: $startYear,
                endYear: $endYear
            );

            if ($result['success']) {
                $successCount++;
                $totalRecords += $result['records_synced'];

                $this->info("  OK: {$result['records_synced']} registros sincronizados.");
            } else {
                $errorCount++;

                $this->error("  ERROR: {$result['message']}");
            }
        }

        $this->newLine();
        $this->info('Sincronización finalizada.');
        $this->line("Indicadores exitosos: {$successCount}");
        $this->line("Indicadores con error: {$errorCount}");
        $this->line("Registros sincronizados: {$totalRecords}");

        return $errorCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
