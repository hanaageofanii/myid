<?php

namespace App\Filament\Resources\VerifikasiDajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\verifikasi_dajam;

class verifikasiDajamStats extends BaseWidget
{
    protected static ?int $sort = 11;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Verifikasi Dajam';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Dajam', verifikasi_dajam::count())
            ->extraAttributes([
                'style' => 'background-color: #5bc162; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', verifikasi_dajam::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #5bc162; border-color: #234C63;'
            ]),      
            Card::make('BTN Cikarang', verifikasi_dajam::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #5bc162; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', verifikasi_dajam::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', verifikasi_dajam::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', verifikasi_dajam::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', verifikasi_dajam::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', verifikasi_dajam::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', verifikasi_dajam::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),
            Card::make('Sudah Diajukan', verifikasi_dajam::where('status_dajam', 'sudah_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),
                Card::make('Belum Diajukan', verifikasi_dajam::where('status_dajam', 'belum_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #5bc162; border-color: #234C63;'
                ]),
        ];
    }
}

