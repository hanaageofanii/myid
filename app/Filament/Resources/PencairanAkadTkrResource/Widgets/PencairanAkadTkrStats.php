<?php

namespace App\Filament\Resources\PencairanAkadTkrTkrResource\Widgets;

// use App\Models\PencairanAkadTkr;

use App\Models\PencairanAkadTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class PencairanAkadTkrStats extends BaseWidget
{
    protected static ?int $sort = 8;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pencairan Akad TKR';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Pencairan Akad', PencairanAkadTkr::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),

            Card::make('BTN Cikarang', PencairanAkadTkr::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', PencairanAkadTkr::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', PencairanAkadTkr::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', PencairanAkadTkr::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', PencairanAkadTkr::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', PencairanAkadTkr::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', PencairanAkadTkr::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),


            // Card::make('Total Max KPR', 'Rp ' . number_format(PencairanAkadTkr::sum('max_kpr'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // Card::make('Total Nilai Pencairan', 'Rp ' . number_format(PencairanAkadTkr::sum('nilai_pencairan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // Card::make('Total Dana Jaminan', 'Rp ' . number_format(PencairanAkadTkr::sum('dana_jaminan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // // Rata-rata Max KPR
            // Card::make('Rata-rata Max KPR', 'Rp ' . number_format(PencairanAkadTkr::avg('max_kpr'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // // Rata-rata Dana Jaminan
            // Card::make('Rata-rata Dana Jaminan', 'Rp ' . number_format(PencairanAkadTkr::avg('dana_jaminan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // // Total Pencairan
            // Card::make('Rata - Rata Nilai Pencairan', 'Rp ' . number_format(PencairanAkadTkr::avg('nilai_pencairan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),        
        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}
