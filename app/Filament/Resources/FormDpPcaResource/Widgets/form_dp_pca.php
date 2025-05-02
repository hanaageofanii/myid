<?php

namespace App\Filament\Resources\FormDpPcaResource\Widgets;

use App\Models\form_dp_pca as ModelsForm_dp_pca;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class form_dp_pca extends BaseWidget
{
    protected static ?int $sort = 7;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Uang Muka PCA';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Uang Muka', ModelsForm_dp_pca::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),           
            Card::make('Total Site Plan', ModelsForm_dp_pca::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Pembayaran Cash', ModelsForm_dp_pca::where('pembayaran', 'cash')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Pembayaran Promo', ModelsForm_dp_pca::where('pembayaran', 'promo')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Pemabayaran Potong Komisi', ModelsForm_dp_pca::where('pembayaran', 'potong_komisi')->count())
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
