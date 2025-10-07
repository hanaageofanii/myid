<?php

namespace App\Filament\Resources\GcvFakturResource\Widgets;

use App\Models\gcv_faktur;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Facades\Filament;

class GcvFakturStats extends BaseWidget
{
    protected static ?int $sort = 6;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Faktur TKR';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_faktur::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Data Faktur', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Faktur Unit Standar', (clone $query)->where('kavling', 'standar')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Faktur Unit Khusus', (clone $query)->where('kavling', 'khusus')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Faktur Unit Hook', (clone $query)->where('kavling', 'hook')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Faktur Unit Komersil', (clone $query)->where('kavling', 'komersil')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Faktur Unit Tanah Lebih', (clone $query)->where('kavling', 'tanah_lebih')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Faktur Unit Kios', (clone $query)->where('kavling', 'kios')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Tarif 11%', (clone $query)->where('tarif_ppn', '11%')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Tarif 12%', (clone $query)->where('tarif_ppn', '12%')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Status DTP', (clone $query)->where('status_ppn', 'dtp')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Status DTP Sebagian', (clone $query)->where('status_ppn', 'dtp_sebagian')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Status Dibebaskan', (clone $query)->where('status_ppn', 'dibebaskan')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Status Bayar', (clone $query)->where('status_ppn', 'bayar')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; border-color: #234C63;'
                ]),
        ];
    }
}