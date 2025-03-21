<?php

namespace App\Filament\Resources\AjbResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\ajb;


class AjbStats extends BaseWidget
{
    protected static ?int $sort = 9;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard AJB';

    protected function getStats(): array
    {
        return [
            Card::make('Total Data AJB', ajb::count())
            ->extraAttributes([
                'style' => 'background-color: #85c1e9; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', ajb::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #85c1e9; border-color: #234C63;'
            ]),            
        ];
    }
}
