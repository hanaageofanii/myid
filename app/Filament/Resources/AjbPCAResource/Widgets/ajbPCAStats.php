<?php

namespace App\Filament\Resources\AjbPCAResource\Widgets;

use App\Models\ajbPCA;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\ajb;

class ajbPCAStats extends BaseWidget
{
    protected static ?int $sort = 19;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard AJB TKR';

    protected function getStats(): array
    {
        return [
            Card::make('Total Data AJB', ajbPca::count())
            ->extraAttributes([
                'style' => 'background-color: #FFFF; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', ajbPca::distinct('siteplan')->count('siteplan'))
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

