<?php

namespace App\Filament\Resources\PengajuanDajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\pengajuan_dajam;



class PenDajamStats extends BaseWidget
{
    protected static ?int $sort = 12;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pangajuan Dajam';
    protected function getStats(): array
    {
        return [
            Card::make('Total Pengajuan Dajam', pengajuan_dajam::count())
            ->extraAttributes([
                'style' => 'background-color: #be9a60; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', pengajuan_dajam::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #be9a60; border-color: #234C63;'
            ]),      
            Card::make('BTN Cikarang', pengajuan_dajam::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #be9a60; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', pengajuan_dajam::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', pengajuan_dajam::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', pengajuan_dajam::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', pengajuan_dajam::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', pengajuan_dajam::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', pengajuan_dajam::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Sudah Diajukan', pengajuan_dajam::where('status_dajam', 'sudah_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Belum Diajukan', pengajuan_dajam::where('status_dajam', 'belum_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Dajam Sertifikat', pengajuan_dajam::where('nama_dajam', 'sertifikat')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Dajam IMB', pengajuan_dajam::where('nama_dajam', 'imb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Dajam Listrik', pengajuan_dajam::where('nama_dajam', 'listrik')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Dajam JKK', pengajuan_dajam::where('nama_dajam', 'jkk')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Dajam PPH', pengajuan_dajam::where('nama_dajam', 'pph')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
            Card::make('Dajam BPHTB', pengajuan_dajam::where('nama_dajam', 'bphtb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #be9a60; border-color: #234C63;'
                ]),
        ];
    }
}

