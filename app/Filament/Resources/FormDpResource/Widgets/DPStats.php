<?php

namespace App\Filament\Resources\FormDpResource\Widgets;

use App\Models\form_dp;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class DPStats extends BaseWidget
{
    protected static ?int $sort = 7;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Uang Muka GCV';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Uang Muka', form_dp::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),           
            Card::make('Total Site Plan', form_dp::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Pembayaran Cash', form_dp::where('pembayaran', 'cash')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Pembayaran Promo', form_dp::where('pembayaran', 'promo')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Pemabayaran Potong Komisi', form_dp::where('pembayaran', 'potong_komisi')->count())
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
