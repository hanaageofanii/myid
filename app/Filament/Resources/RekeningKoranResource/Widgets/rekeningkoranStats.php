<?php

namespace App\Filament\Resources\RekeningKoranResource\Widgets;

use App\Models\rekening_koran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Carbon;

class rekeningkoranStats extends BaseWidget
{

    protected static ?int $sort = 15;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Rekening Koran';
    protected function getCards(): array
    {
        $all = rekening_koran::all();

        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        $thisMonthTrans = rekening_koran::whereMonth('tanggal_mutasi', $now->month)->count();
        $lastMonthTrans = rekening_koran::whereMonth('tanggal_mutasi', $lastMonth->month)->count();

        $chartDataTrans = [$lastMonthTrans, $thisMonthTrans];

        $totalDebit = $all->where('tipe', 'debit')->sum('nominal');
        $totalKredit = $all->where('tipe', 'kredit')->sum('nominal');

        $chartDataDebit = [
            rekening_koran::whereMonth('tanggal_mutasi', $lastMonth->month)->where('tipe', 'debit')->sum('nominal'),
            rekening_koran::whereMonth('tanggal_mutasi', $now->month)->where('tipe', 'debit')->sum('nominal')
        ];

        $chartDataKredit = [
            rekening_koran::whereMonth('tanggal_mutasi', $lastMonth->month)->where('tipe', 'kredit')->sum('nominal'),
            rekening_koran::whereMonth('tanggal_mutasi', $now->month)->where('tipe', 'kredit')->sum('nominal')
        ];

        $lastSaldo = rekening_koran::orderBy('tanggal_mutasi', 'desc')->first()?->saldo ?? 0;

        $chartDataSaldo = [
            rekening_koran::whereMonth('tanggal_mutasi', $lastMonth->month)->orderBy('tanggal_mutasi', 'desc')->first()?->saldo ?? 0,
            rekening_koran::whereMonth('tanggal_mutasi', $now->month)->orderBy('tanggal_mutasi', 'desc')->first()?->saldo ?? 0
        ];

        return [
            Card::make('Total Transaksi', $all->count())
                ->chart($chartDataTrans)
                ->color('success') 
                ->description('Jumlah transaksi per bulan'),

            Card::make('Total Debit', 'Rp ' . number_format(
                $all->where('tipe', 'debit')->sum('nominal'), 0, ',', '.'
            ))
                ->chart($chartDataDebit)
                ->color('primary') 
                ->description('Total Debit per bulan'),

            Card::make('Total Kredit', 'Rp ' . number_format(
                $all->where('tipe', 'kredit')->sum('nominal'), 0, ',', '.'
            ))
                ->chart($chartDataKredit)
                ->color('warning') // Warna kuning
                ->description('Total Kredit per bulan'),

            Card::make('Saldo Terakhir', 'Rp ' . number_format($lastSaldo, 0, ',', '.'))
                ->chart($chartDataSaldo)
                ->color('danger') 
                ->description('Saldo Terakhir per bulan'),
        ];
    }
}
