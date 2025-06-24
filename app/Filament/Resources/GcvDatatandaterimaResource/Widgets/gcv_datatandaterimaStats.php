<?php

namespace App\Filament\Resources\GcvDataTandaTerimaResource\Widgets;

use App\Models\gcv_datatandaterima;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class gcv_datatandaterimaStats extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = null;
    protected static bool $isLazy = false;
    protected int | string | array $columns = 5;
    protected ?string $heading = 'Dashboard Statistik GCV Data Tanda Terima';
    protected function getStats(): array
    {
        $total = gcv_datatandaterima::count();
        $totalAkad = gcv_datatandaterima::where('status', 'akad')->count();
        $bnSudah = gcv_datatandaterima::where('status_bn', 'sudah')->count();
        $bnBelum = gcv_datatandaterima::where('status_bn', 'belum')->count();
        $kavlingStandar = gcv_datatandaterima::where('kavling', 'standar')->count();
        $kavlingKomersil = gcv_datatandaterima::where('kavling', 'komersil')->count();
        $kavlingHook = gcv_datatandaterima::where('kavling', 'hook')->count();
        $kavlingTanahLebih = gcv_datatandaterima::where('kavling', 'tanah_lebih')->count();
        $kavlingKhusus = gcv_datatandaterima::where('kavling', 'khusus')->count();
        $kavlingKios = gcv_datatandaterima::where('kavling', 'kios')->count();
        return [
            Stat::make('Total Data Tanda Terima', $total)
                ->description('Semua data kavling yang terdaftar'),
            Stat::make('Status Akad', $totalAkad)
                ->description('Jumlah kavling yang sudah akad')
                ->color('success'),
            Stat::make('BN Sudah Selesai', $bnSudah)
                ->description('Penyelesaian BN selesai')
                ->color('primary'),
            Stat::make('BN Belum Selesai', $bnBelum)
                ->description('Penyelesaian BN belum selesai')
                ->color('danger'),
            Stat::make('Kavling Standar', $kavlingStandar)
                ->description('Jumlah kavling jenis Standar')
                ->color('info'),
            Stat::make('Kavling Komersil', $kavlingKomersil)
                ->description('Jumlah kavling jenis Komersil')
                ->color('info'),
            Stat::make('Kavling Hook', $kavlingHook)
                ->description('Jumlah kavling jenis Hook')
                ->color('info'),
            Stat::make('Kavling Tanah Lebih', $kavlingTanahLebih)
                ->description('Jumlah kavling jenis Tanah Lebih')
                ->color('info'),
            Stat::make('Kavling Khusus', $kavlingKhusus)
                ->description('Jumlah kavling jenis Khusus')
                ->color('info'),
            Stat::make('Kavling Kios', $kavlingKios)
                ->description('Jumlah kavling jenis Kios')
                ->color('info'),
        ];
    }
}
