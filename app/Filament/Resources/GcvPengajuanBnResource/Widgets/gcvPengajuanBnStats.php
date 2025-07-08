<?php

namespace App\Filament\Resources\GcvPengajuanBnResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_pencairan_bn;
use App\Models\gcv_pengajuan_bn;

class gcvPengajuanBnStats extends BaseWidget
{
 protected static ?int $sort = 13;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pencairan Dajam GCV';
    protected function getStats(): array
    {
        return [
            Card::make('Total Pengajuan BN', gcv_pengajuan_bn::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Total Site Plan', gcv_pengajuan_bn::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Sudah Selesai BN', gcv_pengajuan_bn::where('status_bn', 'sudah')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Belum Selesai BN', gcv_pengajuan_bn::where('status_bn', 'belum')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
        ];
    }
}
