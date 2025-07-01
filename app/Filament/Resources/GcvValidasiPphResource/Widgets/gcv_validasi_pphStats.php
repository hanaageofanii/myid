<?php

namespace App\Filament\Resources\GcvValidasiPphResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\gcv_validasi_pph;
use Filament\Widgets\StatsOverviewWidget\Card;

class gcv_validasi_pphStats extends BaseWidget
{
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Validasi';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Validasi', gcv_validasi_pph::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Total Site Plan', gcv_validasi_pph::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Standar', gcv_validasi_pph::where('kavling', 'standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Khusus', gcv_validasi_pph::where('kavling', 'khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Hook', gcv_validasi_pph::where('kavling', 'hook')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff;form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Komersil', gcv_validasi_pph::where('kavling', 'komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Tanah Lebih', gcv_validasi_pph::where('kavling', 'tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Kios', gcv_validasi_pph::where('kavling', 'kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 1%', gcv_validasi_pph::where('tarif_pph', '1%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 2.5%', gcv_validasi_pph::where('tarif_pph', '2.5%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
        ];
    }
}