<?php

namespace App\Filament\Resources\AjbTkrResource\Widgets;

use App\Models\AjbTkr as ModelsAjbTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
// use App\Models\AjbTkr;

class AjbTkr extends BaseWidget
{
    protected static ?int $sort = 19;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard AJB TKR';

    protected function getStats(): array
    {
        return [
            Card::make('Total Data AJB', ModelsAjbTkr::count())
            ->extraAttributes([
                'style' => 'background-color: #FFFF; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', ModelsAjbTkr::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #FFFF; border-color: #234C63;'
            ]),            
        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}

