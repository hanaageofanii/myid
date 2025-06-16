<?php

namespace App\Filament\Resources\LegalTkrResource\Widgets;

use App\Models\LegalTkr as ModelsLegalTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class LegalTkr extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Legalitas TKR';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Legalitas', ModelsLegalTkr::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Total Site Plan', ModelsLegalTkr::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),  
            Card::make('Status Legalitas Induk', ModelsLegalTkr::where('status_sertifikat', 'induk')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),            
            Card::make('Status Legalitas Pecahan', ModelsLegalTkr::where('status_sertifikat','pecahan')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}
