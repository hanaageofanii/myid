<?php

namespace App\Filament\Resources\GcvVerifikasiDajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_verifikasi_dajam;

class GcvVerifikasiDajamStats extends BaseWidget
{
          
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Verifikasi Dajam GCV';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Dajam', gcv_verifikasi_dajam::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', gcv_verifikasi_dajam::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),      
            Card::make('BTN Cikarang', gcv_verifikasi_dajam::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', gcv_verifikasi_dajam::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', gcv_verifikasi_dajam::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', gcv_verifikasi_dajam::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', gcv_verifikasi_dajam::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', gcv_verifikasi_dajam::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', gcv_verifikasi_dajam::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Sudah Diajukan', gcv_verifikasi_dajam::where('status_dajam', 'sudah_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Belum Diajukan', gcv_verifikasi_dajam::where('status_dajam', 'belum_diajukan')->count())
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