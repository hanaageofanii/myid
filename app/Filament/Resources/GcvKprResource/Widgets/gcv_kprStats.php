<?php

namespace App\Filament\Resources\GcvKprResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_kpr;
use Filament\Facades\Filament;

class gcv_kprStats extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard KPR GCV';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_kpr::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Data Penjualan KPR', $query->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Status Akad', (clone $query)->where('status_akad', 'akad')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Status Batal Akad', (clone $query)->where('status_akad','batal')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Total Unit Standar', (clone $query)->where('jenis_unit','standar')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Total Unit Khusus', (clone $query)->where('jenis_unit','khusus')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Total Unit Hook', (clone $query)->where('jenis_unit','hook')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Total Unit Komersil', (clone $query)->where('jenis_unit','komersil')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Total Unit Tanah Lebih', (clone $query)->where('jenis_unit','tanah_lebih')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Total Unit Kios', (clone $query)->where('jenis_unit','kios')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),
        ];
    }
}
