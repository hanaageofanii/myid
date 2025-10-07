<?php

namespace App\Filament\Resources\GcvPencairanDajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_pencairan_dajam;
use Filament\Facades\Filament;

class gcv_pencairan_dajamStats extends BaseWidget
{
    protected static ?int $sort = 13;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pencairan Dajam GCV';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_pencairan_dajam::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Pencairan Dajam', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam Sertifikat', (clone $query)->where('nama_dajam', 'sertifikat')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam IMB', (clone $query)->where('nama_dajam', 'imb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam Listrik', (clone $query)->where('nama_dajam', 'listrik')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam Bestek', (clone $query)->where('nama_dajam', 'bestek')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam JKK', (clone $query)->where('nama_dajam', 'jkk')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam PPH', (clone $query)->where('nama_dajam', 'pph')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam BPHTB', (clone $query)->where('nama_dajam', 'bphtb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
}