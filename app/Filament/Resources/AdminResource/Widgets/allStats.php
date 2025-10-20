<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\User;
use App\Models\kartu_kontrolGCV;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;

class allStats extends BaseWidget
{
    protected ?string $heading = 'Statistik Aktivitas User';

    protected function getCards(): array
    {
        $today = Carbon::today();

        $userAktifHariIni = kartu_kontrolGCV::whereDate('created_at', $today)
            ->distinct()
            ->pluck('user_id');

        $totalUserAktif = $userAktifHariIni->count();
        $userTidakAktif = User::whereNotIn('id', $userAktifHariIni)->count();

        return [
            Card::make('Total User', User::count())
                ->description('Jumlah semua user di sistem')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Card::make('User Aktif Hari Ini', $totalUserAktif)
                ->description('User yang input data hari ini')
                ->icon('heroicon-o-check-badge')
                ->color($totalUserAktif > 0 ? 'success' : 'warning'),

            Card::make('User Tidak Aktif Hari Ini', $userTidakAktif)
                ->description('Belum input data hari ini')
                ->icon('heroicon-o-x-circle')
                ->color($userTidakAktif > 0 ? 'danger' : 'success'),
        ];
    }
}