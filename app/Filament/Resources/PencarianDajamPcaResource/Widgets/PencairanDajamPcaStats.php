<?php

namespace App\Filament\Resources\PencairanDajamPcaResource\Widgets;

use App\Models\pencairan_dajam_pca;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PencairanDajamPcaStats extends BaseWidget

{
    protected static ?int $sort = 13;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pencairan Dajam PCA';
    protected function getStats(): array
    {
        return [
            Card::make('Total Pencairan Dajam', pencairan_dajam_pca::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', pencairan_dajam_pca::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),      
            Card::make('BTN Cikarang', pencairan_dajam_pca::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', pencairan_dajam_pca::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', pencairan_dajam_pca::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', pencairan_dajam_pca::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', pencairan_dajam_pca::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', pencairan_dajam_pca::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', pencairan_dajam_pca::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Sertifikat', pencairan_dajam_pca::where('nama_dajam', 'sertifikat')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam IMB', pencairan_dajam_pca::where('nama_dajam', 'imb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Listrik', pencairan_dajam_pca::where('nama_dajam', 'listrik')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam Bestek', pencairan_dajam_pca::where('nama_dajam', 'bestek')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            
            Card::make('Dajam JKK', pencairan_dajam_pca::where('nama_dajam', 'jkk')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam PPH', pencairan_dajam_pca::where('nama_dajam', 'pph')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Dajam BPHTB', pencairan_dajam_pca::where('nama_dajam', 'bphtb')->count())
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
