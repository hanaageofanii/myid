<?php

namespace App\Filament\Resources\RekonsilResource\Widgets;

use App\Models\rekonsil;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class rekonsilStats extends BaseWidget
{

    protected static ?int $sort = 14;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Rekonsil';
    protected function getStats(): array
    {
        $totalDebit = rekonsil::where('tipe', 'debit')->sum('jumlah_uang');
        $totalKredit = rekonsil::where('tipe', 'kredit')->sum('jumlah_uang');

        $totalTransaksi = rekonsil::count();

        $sudah = rekonsil::where('status_rekonsil', 'sudah')->count();
        $belum = rekonsil::where('status_rekonsil', 'belum')->count();

        $debit = rekonsil::where('tipe', 'debit')->count();
        $kredit = rekonsil::where('tipe', 'kredit')->count();

        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        $thisMonthTrans = rekonsil::whereMonth('tanggal_transaksi', $now->month)->count();
        $lastMonthTrans = rekonsil::whereMonth('tanggal_transaksi', $lastMonth->month)->count();

        $chartData = [$lastMonthTrans, $thisMonthTrans];

        return [
            Stat::make('Total Uang Masuk (Debit)', 'Rp ' . number_format($totalDebit, 0, ',', '.'))
                ->description('Total uang masuk (Debit)')
                ->color('success')
                ->chart($chartData), 

            Stat::make('Total Uang Keluar (Kredit)', 'Rp ' . number_format($totalKredit, 0, ',', '.'))
                ->description('Total uang keluar (Kredit)')
                ->color('danger')
                ->chart($chartData), 

            Stat::make('Jumlah Transaksi', $totalTransaksi)
                ->description('Total jumlah transaksi')
                ->color('primary')
                ->chart($chartData), 

            Stat::make('Status Rekonsiliasi', "$sudah Sudah / $belum Belum")
                ->description('Perbandingan status rekonsiliasi')
                ->color('primary')
                ->chart($chartData), 


            Stat::make('Tipe Transaksi', "$debit Debit / $kredit Kredit")
                ->description('Perbandingan tipe transaksi Debit / Kredit')
                ->color('primary')
                ->chart($chartData), 

        ];
    }
}
