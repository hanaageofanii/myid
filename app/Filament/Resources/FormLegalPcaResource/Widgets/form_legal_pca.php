<?php

namespace App\Filament\Resources\FormLegalPcaResource\Widgets;

use App\Models\form_legal_pca as ModelsForm_legal_pca;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class form_legal_pca extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Sertifikat PCA';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Sertifikat', ModelsForm_legal_pca::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Total Site Plan', ModelsForm_legal_pca::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),  
            Card::make('Status Sertifikat Induk', ModelsForm_legal_pca::where('status_sertifikat', 'induk')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),            
            Card::make('Status Sertfikat Pecahan', ModelsForm_legal_pca::where('status_sertifikat','pecahan')->count())
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
