<?php

namespace App\Filament\Resources\CekPerjalananResource\Widgets;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use App\Models\cek_perjalanan;


class cek_perjalananStats extends BaseWidget
{
    protected static ?int $sort = 14;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Cek Rekonsil & Transaksi';
    protected function getStats(): array
    {
        $totalPencairan = cek_perjalanan::count();
        $sudahDisalurkan = cek_perjalanan::where('status_disalurkan', 'sudah')->count();
        $belumDisalurkan = cek_perjalanan::where('status_disalurkan', 'belum')->count();

        $thisMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth()->month;

        $pencairanThisMonth = cek_perjalanan::whereMonth('tanggal_dicairkan', $thisMonth)->count();
        $pencairanLastMonth = cek_perjalanan::whereMonth('tanggal_dicairkan', $lastMonth)->count();

        $chartData = [$pencairanLastMonth, $pencairanThisMonth];

        $tujuanDana = cek_perjalanan::select('tujuan_dana')
            ->get()
            ->groupBy('tujuan_dana')
            ->map(fn($group) => $group->count());

        return [
            Stat::make('Total Pencairan Dana', $totalPencairan)
                ->description('Total data pencairan')
                ->color('primary')
                ->chart($chartData),

            Stat::make('Status Penyaluran', "$sudahDisalurkan Sudah / $belumDisalurkan Belum")
                ->description('Status dana yang disalurkan')
                ->color('success')
                ->chart($chartData),

            Stat::make('Tujuan Dana Teratas', $tujuanDana->sortDesc()->keys()->first() ?? 'Tidak ada')
                ->description('Tujuan dana yang paling sering')
                ->color('warning'),
        ];
    }
}
