<?php

namespace App\Filament\Resources\GcvPengajuanBnResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_pengajuan_bn;
use Filament\Facades\Filament;

class gcvPengajuanBnStats extends BaseWidget
{
    protected static ?int $sort = 13;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pengajuan BN GCV';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_pengajuan_bn::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Pengajuan BN', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Sudah Selesai BN', (clone $query)->where('status_bn', 'sudah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Belum Selesai BN', (clone $query)->where('status_bn', 'belum')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
}