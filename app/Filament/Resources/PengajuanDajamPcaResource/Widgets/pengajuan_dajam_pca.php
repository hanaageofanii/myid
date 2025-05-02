<?php

namespace App\Filament\Resources\PengajuanDajamPcaResource\Widgets;

use App\Models\pengajuan_dajam_pca as ModelsPengajuan_dajam_pca;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class pengajuan_dajam_pca extends BaseWidget
{
    protected static ?int $sort = 12;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pangajuan Dajam PCA';
    protected function getStats(): array
    {
        return [
            Card::make('Total Pengajuan Dajam', ModelsPengajuan_dajam_pca::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', ModelsPengajuan_dajam_pca::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),      
            Card::make('BTN Cikarang', ModelsPengajuan_dajam_pca::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', ModelsPengajuan_dajam_pca::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', ModelsPengajuan_dajam_pca::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', ModelsPengajuan_dajam_pca::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', ModelsPengajuan_dajam_pca::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', ModelsPengajuan_dajam_pca::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', ModelsPengajuan_dajam_pca::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Sudah Diajukan', ModelsPengajuan_dajam_pca::where('status_dajam', 'sudah_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Belum Diajukan', ModelsPengajuan_dajam_pca::where('status_dajam', 'belum_diajukan')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Sertifikat', ModelsPengajuan_dajam_pca::where('nama_dajam', 'sertifikat')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam IMB', ModelsPengajuan_dajam_pca::where('nama_dajam', 'imb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Listrik', ModelsPengajuan_dajam_pca::where('nama_dajam', 'listrik')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Dajam Bestek', ModelsPengajuan_dajam_pca::where('nama_dajam', 'bestek')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam JKK', ModelsPengajuan_dajam_pca::where('nama_dajam', 'jkk')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam PPH', ModelsPengajuan_dajam_pca::where('nama_dajam', 'pph')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam BPHTB', ModelsPengajuan_dajam_pca::where('nama_dajam', 'bphtb')->count())
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

