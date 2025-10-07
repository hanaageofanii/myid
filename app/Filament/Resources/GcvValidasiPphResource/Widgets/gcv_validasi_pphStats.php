<?php

namespace App\Filament\Resources\GcvValidasiPphResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\gcv_validasi_pph;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Facades\Filament;

class gcv_validasi_pphStats extends BaseWidget
{
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Validasi';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_validasi_pph::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Data Validasi', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Unit Standar', (clone $query)->where('kavling', 'standar')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Unit Khusus', (clone $query)->where('kavling', 'khusus')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Unit Hook', (clone $query)->where('kavling', 'hook')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff;form_pajak; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Unit Komersil', (clone $query)->where('kavling', 'komersil')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Unit Tanah Lebih', (clone $query)->where('kavling', 'tanah_lebih')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Unit Kios', (clone $query)->where('kavling', 'kios')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Tarif 1%', (clone $query)->where('tarif_pph', '1%')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
                ]),

            Card::make('Jumlah Validasi Tarif 2.5%', (clone $query)->where('tarif_pph', '2.5%')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
                ]),
        ];
    }
}