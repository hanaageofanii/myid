<?php

namespace App\Filament\Resources\GcvPencairanDajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_pencairan_dajam;

class gcv_pencairan_dajamStats extends BaseWidget
{
   protected static ?int $sort = 13;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pencairan Dajam GCV';
    protected function getStats(): array
    {
        return [
            Card::make('Total Pencairan Dajam', gcv_pencairan_dajam::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Total Site Plan', gcv_pencairan_dajam::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            // Card::make('BTN Cikarang', gcv_pencairan_dajam::where('bank', 'BTN Cikarang')->count())
            // ->extraAttributes([
            //     'style' => 'background-color: #ffff; border-color: #234C63;'
            // ]),

            // Card::make('BTN Bekasi', gcv_pencairan_dajam::where('bank', 'btn_bekasi')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // Card::make('BTN Karawang', gcv_pencairan_dajam::where('bank', 'btn_karawang')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // Card::make('BJB Syariah', gcv_pencairan_dajam::where('bank', 'bjb_syariah')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // Card::make('BJB Jababeka', gcv_pencairan_dajam::where('bank', 'bjb_jababeka')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // Card::make('BTN Syariah', gcv_pencairan_dajam::where('bank', 'btn_syariah')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            // Card::make('BRI Bekasi', gcv_pencairan_dajam::where('bank', 'brii_bekasi')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),
            Card::make('Dajam Sertifikat', gcv_pencairan_dajam::where('nama_dajam', 'sertifikat')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam IMB', gcv_pencairan_dajam::where('nama_dajam', 'imb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Listrik', gcv_pencairan_dajam::where('nama_dajam', 'listrik')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Bestek', gcv_pencairan_dajam::where('nama_dajam', 'bestek')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam JKK', gcv_pencairan_dajam::where('nama_dajam', 'jkk')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam PPH', gcv_pencairan_dajam::where('nama_dajam', 'pph')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam BPHTB', gcv_pencairan_dajam::where('nama_dajam', 'bphtb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
}
