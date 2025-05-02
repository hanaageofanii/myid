<?php

namespace App\Filament\Resources\FormKprPcaResource\Widgets;

use App\Models\form_kpr_pca as ModelsForm_kpr_pca;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class form_kpr_pca extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard KPR PCA';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Penjualan KPR', ModelsForm_kpr_pca::count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', ModelsForm_kpr_pca::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),      
            Card::make('Status Akad', ModelsForm_kpr_pca::where('status_akad', 'akad')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),            
            Card::make('Status Batal Akad', ModelsForm_kpr_pca::where('status_akad','batal')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),   
            Card::make('Total Unit Standar', ModelsForm_kpr_pca::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Standar', ModelsForm_kpr_pca::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Standar', ModelsForm_kpr_pca::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Khusus', ModelsForm_kpr_pca::where('jenis_unit','khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Hook', ModelsForm_kpr_pca::where('jenis_unit','hook')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Komersil', ModelsForm_kpr_pca::where('jenis_unit','komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Tanah Lebih', ModelsForm_kpr_pca::where('jenis_unit','tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Kios', ModelsForm_kpr_pca::where('jenis_unit','kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  

        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}


