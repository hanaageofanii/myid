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
    protected ?string $heading = 'Dashboard Data Uang Muka';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Uang Muka', form_dp::count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),           
            Card::make('Total Site Plan', form_dp::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),
            Card::make('Pembayaran Cash', form_dp::where('pembayaran', 'cash')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]), 
            Card::make('Pembayaran Promo', form_dp::where('pembayaran', 'promo')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]), 
            Card::make('Pemabayaran Potong Komisi', form_dp::where('pembayaran', 'potong_komisi')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]), 
        ];
    }
}
