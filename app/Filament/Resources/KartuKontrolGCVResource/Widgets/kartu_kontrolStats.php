<?php

namespace App\Filament\Resources\KartuKontrolGCVResource\Widgets;

use App\Models\kartu_kontrolGCV;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Facades\Filament;


class kartu_kontrolStats extends BaseWidget
{
    protected function getCards(): array
    {
        $tenant = Filament::getTenant();

        $query = kartu_kontrolGCV::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        $today = Carbon::today();
        $startOfMonth = now()->startOfMonth();

        // Statistik dasar
        $totalData = $query->count();
        $totalHariIni = (clone $query)->whereDate('created_at', $today)->count();
        $totalBulanIni = (clone $query)->whereBetween('created_at', [$startOfMonth, now()])->count();

        // Statistik tambahan
        $totalHargaJual = (clone $query)->sum('harga_jual');
        $totalProyek = (clone $query)->distinct('proyek')->count('proyek');

        return [
            Card::make('Total Data', number_format($totalData))
                ->icon('heroicon-o-rectangle-stack')
                ->color('primary'),

            Card::make('Input Hari Ini', number_format($totalHariIni))
                ->description('Jumlah data masuk hari ini')
                ->icon('heroicon-o-calendar')
                ->color($totalHariIni > 0 ? 'success' : 'warning'),

            Card::make('Input Bulan Ini', number_format($totalBulanIni))
                ->description('Data bulan ' . now()->translatedFormat('F'))
                ->icon('heroicon-o-calendar-days')
                ->color('info'),

            Card::make('Total Harga Jual', 'Rp ' . number_format($totalHargaJual, 0, ',', '.'))
                ->description('Akumulasi harga jual tim ini')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Card::make('Jumlah Proyek Aktif', $totalProyek)
                ->description('Total proyek unik tim ini')
                ->icon('heroicon-o-building-office')
                ->color('secondary'),
        ];
    }
}