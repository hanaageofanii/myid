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
    protected ?string $heading = 'Dashboard Statistik GCV Data Tanda Terima';


    protected function getStats(): array
    {
        return [
            Stat::make('Total Data', gcv_datatandaterima::count()),
            Stat::make('Status Akad', gcv_datatandaterima::where('status', 'akad')->count())
                ->color('success'),
            Stat::make('Status Sudah BN', gcv_datatandaterima::where('status_bn', 'sudah')->count())
                ->color('primary'),
            Stat::make('Status Belum BN', gcv_datatandaterima::where('status_bn', 'belum')->count())
                ->color('danger'),
            Stat::make('Kavling Standar', gcv_datatandaterima::where('kavling', 'standar')->count())
                ->color('info'),
            Stat::make('Kavling Komersil', gcv_datatandaterima::where('kavling', 'komersil')->count())
                ->color('info'),
            Stat::make('Kavling Hook', gcv_datatandaterima::where('kavling', 'hook')->count())
                ->color('info'),
            Stat::make('Kavling Tanah Lebih', gcv_datatandaterima::where('kavling', 'tanah_lebih')->count())
                ->color('info'),
            Stat::make('Kavling Khusus', gcv_datatandaterima::where('kavling', 'khusus')->count())
                ->color('info'),
            Stat::make('Kavling Kios', gcv_datatandaterima::where('kavling', 'kios')->count())
                ->color('info'),
        ];
    }
}
