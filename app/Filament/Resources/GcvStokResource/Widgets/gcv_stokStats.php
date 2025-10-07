<?php

namespace App\Filament\Resources\GcvStokResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_stok;
use Filament\Facades\Filament;

class gcv_stokStats extends BaseWidget
{
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Bookingan';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_stok::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Data Tkr', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Total Tkr', (clone $query)->where('proyek', 'tkr')->count())
                ->color('white')
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63; color: #234C63; font-weight: bold;'
                ]),

            Card::make('Total Tkr Cira', (clone $query)->where('proyek', 'tkr_cira')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
}
