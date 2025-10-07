<?php

namespace App\Filament\Resources\GcvDatatanahResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\GcvDatatanahResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\gcv_datatanah;
use Filament\Facades\Filament;

class gcv_datatanahStats extends BaseWidget
{
    protected static string $resource = GcvDatatanahResource::class;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Statistik Data Tanah';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_datatanah::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        $totalData = $query->count();
        $totalLuasSurat = (clone $query)->sum('luas_surat');
        $totalLuasUkur = (clone $query)->sum('luas_ukur');

        return [
            Stat::make('Total Data Tanah', $totalData)->color('primary'),
            Stat::make('Total Luas Surat', number_format($totalLuasSurat, 2, ',', '.'))->color('success'),
            Stat::make('Total Luas Ukur', number_format($totalLuasUkur, 2, ',', '.'))->color('secondary'),
        ];
    }
}
