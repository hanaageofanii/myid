<?php

namespace App\Filament\Resources\FormDpTkrResource\Widgets;

use App\Models\FormDpTkr as ModelsFormDpTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;

class FormDpTkr extends BaseWidget
{ protected static ?int $sort = 7;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Uang Muka TKR';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Uang Muka', ModelsFormDpTkr::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),           
            Card::make('Total Site Plan', ModelsFormDpTkr::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Pembayaran Cash', ModelsFormDpTkr::where('pembayaran', 'cash')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Pembayaran Promo', ModelsFormDpTkr::where('pembayaran', 'promo')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Pemabayaran Potong Komisi', ModelsFormDpTkr::where('pembayaran', 'potong_komisi')->count())
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
