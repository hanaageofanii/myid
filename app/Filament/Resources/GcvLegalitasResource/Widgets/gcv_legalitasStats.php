<?php

namespace App\Filament\Resources\GcvLegalitasResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_legalitas;
use Illuminate\Support\Facades\Auth;


class gcv_legalitasStats extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Legalitas PCA';
protected function getStats(): array
{
    $teamId = Auth::user()->team_id; // Ambil team pengguna saat ini

    return [
        Card::make('Total Data Legalitas', gcv_legalitas::where('team_id', $teamId)->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
        Card::make('Total Site Plan', gcv_legalitas::where('team_id', $teamId)->distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
        Card::make('Status Legalitas Induk', gcv_legalitas::where('team_id', $teamId)->where('status_sertifikat', 'induk')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
        Card::make('Status Legalitas Pecahan', gcv_legalitas::where('team_id', $teamId)->where('status_sertifikat', 'pecahan')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
    ];
}
}
