<?php

namespace App\Filament\Resources\GcvDatatanahResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\GcvDatatanahResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\gcv_datatanah;

class gcv_datatanahStats extends BaseWidget
{
    protected static string $resource = GcvDatatanahResource::class;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Statistik Data Tanah';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Data Tanah', gcv_datatanah::count()),
        ];
    }
}
