<?php

namespace App\Filament\Resources\FormPpnPcaResource\Widgets;

use App\Models\form_ppn_pca as ModelsForm_ppn_pca;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class form_ppn_pca extends BaseWidget
{
    protected static ?int $sort = 6;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Faktur PCA';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Faktur', ModelsForm_ppn_pca::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Total Site Plan', ModelsForm_ppn_pca::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),  
            Card::make('Jumlah Faktur Unit Standar', ModelsForm_ppn_pca::where('kavling', 'standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),            
            Card::make('Jumlah Faktur Unit Khusus', ModelsForm_ppn_pca::where('kavling', 'khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Hook', ModelsForm_ppn_pca::where('kavling', 'hook')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff;form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Komersil', ModelsForm_ppn_pca::where('kavling', 'komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Tanah Lebih', ModelsForm_ppn_pca::where('kavling', 'tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Kios', ModelsForm_ppn_pca::where('kavling', 'kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 11%', ModelsForm_ppn_pca::where('tarif_ppn', '11%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 12%', ModelsForm_ppn_pca::where('tarif_ppn', '12%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status DTP', ModelsForm_ppn_pca::where('status_ppn', 'dtp')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status DTP Sebagian', ModelsForm_ppn_pca::where('status_ppn', 'dtp_sebagian')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status Dibebaskan', ModelsForm_ppn_pca::where('status_ppn', 'dibebaskan')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status Bayar', ModelsForm_ppn_pca::where('status_ppn', 'bayar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),

        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}
