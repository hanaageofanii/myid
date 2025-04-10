<?php

namespace App\Filament\Resources\PencairanAkadResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\PencairanAkad;

class AkadStats extends BaseWidget
{
    protected static ?int $sort = 8;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pencairan Akad';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Pencairan Akad', PencairanAkad::count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),

            Card::make('BTN Cikarang', PencairanAkad::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', PencairanAkad::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', PencairanAkad::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', PencairanAkad::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', PencairanAkad::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', PencairanAkad::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', PencairanAkad::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),


            // Card::make('Total Max KPR', 'Rp ' . number_format(PencairanAkad::sum('max_kpr'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #FFC85B; border-color: #234C63;'
            //     ]),

            // Card::make('Total Nilai Pencairan', 'Rp ' . number_format(PencairanAkad::sum('nilai_pencairan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #FFC85B; border-color: #234C63;'
            //     ]),

            // Card::make('Total Dana Jaminan', 'Rp ' . number_format(PencairanAkad::sum('dana_jaminan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #FFC85B; border-color: #234C63;'
            //     ]),

            // // Rata-rata Max KPR
            // Card::make('Rata-rata Max KPR', 'Rp ' . number_format(PencairanAkad::avg('max_kpr'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #FFC85B; border-color: #234C63;'
            //     ]),

            // // Rata-rata Dana Jaminan
            // Card::make('Rata-rata Dana Jaminan', 'Rp ' . number_format(PencairanAkad::avg('dana_jaminan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #FFC85B; border-color: #234C63;'
            //     ]),

            // // Total Pencairan
            // Card::make('Rata - Rata Nilai Pencairan', 'Rp ' . number_format(PencairanAkad::avg('nilai_pencairan'), 0, ',', '.'))
            //     ->extraAttributes([
            //         'style' => 'background-color: #FFC85B; border-color: #234C63;'
            //     ]),        
        ];
    }
}
