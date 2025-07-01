<?php

namespace App\Filament\Resources\GcvUangMukaResource\Widgets;

use App\Models\gcv_uang_muka;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class gcv_uangMukaStats extends BaseWidget
{
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Uang Muka';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Uang Muka', gcv_uang_muka::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Total Site Plan', gcv_uang_muka::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Pembayaran Cash', gcv_uang_muka::where('pembayaran', 'cash')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Pembayaran Promo', gcv_uang_muka::where('pembayaran', 'promo')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Pemabayaran Potong Komisi', gcv_uang_muka::where('pembayaran', 'potong_komisi')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
        ];
    }
}