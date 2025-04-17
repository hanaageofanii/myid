<?php

namespace App\Filament\Resources\FormPencocokanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\form_pencocokan;
use Filament\Widgets\StatsOverviewWidget\Card;

use Illuminate\Support\Carbon;

// use Filament\Resources\FormPencocokanResource\Widgets\form_pencocokan;


class FormPencocokanStats extends BaseWidget
{

    protected static ?int $sort = 18;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Pencocokan';
    protected function getCards(): array
    {
        return [
            Card::make('Total Transaksi', number_format(form_pencocokan::count()))
                ->description('Semua transaksi yang telah masuk')
                ->color('primary'),

            Card::make('Total Jumlah (Rp)', 'Rp. ' . number_format(form_pencocokan::sum('jumlah')))
                ->description('Jumlah uang dari semua transaksi')
                ->color('success'),

            Card::make('Transaksi Bermasalah', number_format(form_pencocokan::where('selisih', '!=', 0)->count()))
                ->description('Transaksi dengan selisih')
                ->color('danger'),
        ];
    }
}
