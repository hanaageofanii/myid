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
         $teamId = filament()->getTenant()->id;

        $query = gcv_datatandaterima::where('team_id', $teamId);

        return [
            Stat::make('Total Data', $query->count()),

            Stat::make('Status Akad', $query->clone()->where('status', 'akad')->count())
                ->color('success'),

            Stat::make('Status Sudah BN', $query->clone()->where('status_bn', 'sudah')->count())
                ->color('primary'),

            Stat::make('Status Belum BN', $query->clone()->where('status_bn', 'belum')->count())
                ->color('danger'),

            Stat::make('Kavling Standar', $query->clone()->where('kavling', 'standar')->count())
                ->color('info'),

            Stat::make('Kavling Komersil', $query->clone()->where('kavling', 'komersil')->count())
                ->color('info'),

            Stat::make('Kavling Hook', $query->clone()->where('kavling', 'hook')->count())
                ->color('info'),

            Stat::make('Kavling Tanah Lebih', $query->clone()->where('kavling', 'tanah_lebih')->count())
                ->color('info'),

            Stat::make('Kavling Khusus', $query->clone()->where('kavling', 'khusus')->count())
                ->color('info'),

            Stat::make('Kavling Kios', $query->clone()->where('kavling', 'kios')->count())
                ->color('info'),
        ];
    }
}