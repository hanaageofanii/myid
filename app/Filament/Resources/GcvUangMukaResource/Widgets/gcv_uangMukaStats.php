<?php

namespace App\Filament\Resources\GcvUangMukaResource\Widgets;

use App\Models\gcv_uang_muka;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Facades\Filament;

class gcv_uangMukaStats extends BaseWidget
{
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Uang Muka';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_uang_muka::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Data Uang Muka', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Pembayaran Cash', (clone $query)->where('pembayaran', 'cash')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Pembayaran Promo', (clone $query)->where('pembayaran', 'promo')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Pembayaran Potong Komisi', (clone $query)->where('pembayaran', 'potong_komisi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
}