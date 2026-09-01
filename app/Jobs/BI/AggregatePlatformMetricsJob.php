<?php

namespace App\Jobs\BI;

use App\Services\BI\KPIService;
use App\Services\BI\MetricsRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AggregatePlatformMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(KPIService $kpiService): void
    {
        $definitions = MetricsRegistry::getDefinitions();

        foreach (array_keys($definitions) as $kpiCode) {
            $kpiService->calculateMetric($kpiCode);
        }
    }
}
