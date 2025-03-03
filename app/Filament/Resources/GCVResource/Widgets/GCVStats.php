<?php

namespace App\Filament\Resources\GCVResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\GCV;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Support\Colors\Color;

class GCVStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard GCV';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data GCV', GCV::count())
            ->extraAttributes([
                'style' => 'background-color: #B1C29E; border-color: #234C63;'
            ]),               
            Card::make('Total GCV', GCV::where('proyek', 'gcv')->count())
                ->extraAttributes([
                    'style' => 'background-color: #B1C29E; border-color: #234C63;'
                ]),
            Card::make('Total GCV Cira', GCV::where('proyek', 'gcv_cira')->count())
                ->extraAttributes([
                    'style' => 'background-color: #B1C29E; border-color: #234C63;'
                ]),
            Card::make('Total Terbooking', GCV::where('status', 'booking')->count())
                ->extraAttributes([
                    'style' => 'background-color: #B1C29E; border-color: #234C63;'
                ]),  
            Card::make('Total Akad', GCV::where('kpr_status', 'akad')->count())
                ->extraAttributes([
                    'style' => 'background-color: #B1C29E; border-color: #234C63;'
                ]), 
            Card::make('Total SP3K', GCV::where('kpr_status', 'sp3k')->count())
                ->extraAttributes([
                    'style' => 'background-color: #B1C29E; border-color: #234C63;'
                ]), 
            Card::make('Total Batal Akad', GCV::where('kpr_status', 'batal')->count())
                ->extraAttributes([
                    'style' => 'background-color:#B1C29E ; border-color: #234C63;'
                ]), 
        ];
    }
}
