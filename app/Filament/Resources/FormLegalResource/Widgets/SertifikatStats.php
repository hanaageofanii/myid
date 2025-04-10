<?php

namespace App\Filament\Resources\FormLegalResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\form_legal;



class SertifikatStats extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Sertifikat';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Sertifikat', form_legal::count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]), 
            Card::make('Total Site Plan', form_legal::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),  
            Card::make('Status Sertifikat Induk', form_legal::where('status_sertifikat', 'induk')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),            
            Card::make('Status Sertfikat Pecahan', form_legal::where('status_sertifikat','pecahan')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]), 
        ];
    }
}
