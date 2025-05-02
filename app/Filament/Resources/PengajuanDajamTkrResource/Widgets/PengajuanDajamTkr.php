<?php

namespace App\Filament\Resources\PengajuanDajamTkrResource\Widgets;

use App\Models\PengajuanDajamTkr as ModelsPengajuanDajamTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class PengajuanDajamTkr extends BaseWidget
{
    protected static ?int $sort = 12;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pangajuan Dajam TKR';
    protected function getStats(): array
    {
        return [
            Card::make('Total Pengajuan Dajam', ModelsPengajuanDajamTkr::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', ModelsPengajuanDajamTkr::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),      
            Card::make('BTN Cikarang', ModelsPengajuanDajamTkr::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', ModelsPengajuanDajamTkr::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', ModelsPengajuanDajamTkr::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', ModelsPengajuanDajamTkr::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', ModelsPengajuanDajamTkr::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', ModelsPengajuanDajamTkr::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', ModelsPengajuanDajamTkr::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Sudah Diajukan', ModelsPengajuanDajamTkr::where('status_dajam', 'sudah_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Belum Diajukan', ModelsPengajuanDajamTkr::where('status_dajam', 'belum_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Sertifikat', ModelsPengajuanDajamTkr::where('nama_dajam', 'sertifikat')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam IMB', ModelsPengajuanDajamTkr::where('nama_dajam', 'imb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Listrik', ModelsPengajuanDajamTkr::where('nama_dajam', 'listrik')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Dajam Bestek', ModelsPengajuanDajamTkr::where('nama_dajam', 'bestek')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam JKK', ModelsPengajuanDajamTkr::where('nama_dajam', 'jkk')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam PPH', ModelsPengajuanDajamTkr::where('nama_dajam', 'pph')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam BPHTB', ModelsPengajuanDajamTkr::where('nama_dajam', 'bphtb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}

