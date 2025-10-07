<?php

namespace App\Filament\Resources\GcvPencairanAkadResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_pencairan_akad;
use Filament\Facades\Filament;

class gcv_pencairan_akadStats extends BaseWidget
{
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pencairan Akad PCA';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_pencairan_akad::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Data Pencairan Akad', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Cikarang', (clone $query)->where('bank', 'BTN Cikarang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Bekasi', (clone $query)->where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', (clone $query)->where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', (clone $query)->where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', (clone $query)->where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', (clone $query)->where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', (clone $query)->where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
}
