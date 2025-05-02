<?php

namespace App\Filament\Resources\AuditTkrResource\Widgets;

use App\Models\audit_tkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Support\Colors\Color;

class audit_tkrStats extends BaseWidget
{
    protected static ?int $sort = 20;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Audit TKR';
    
    protected function getStats(): array
    {
            return [
                Card::make('Total Data Audit', audit_tkr::count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),    
                Card::make('Total Site Plan', audit_tkr::distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),            
                Card::make('Terbangun', audit_tkr::where('terbangun', 1)->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Belum Terbangun', audit_tkr::where('terbangun', 0)->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Status Akad', audit_tkr::where('status', 'akad')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),            
                Card::make('Status Belum Akad', audit_tkr::whereNotIn('status', ['Stock', 'Akad'])
                ->orWhereNull('status')
                ->orWhere('status', '')
                ->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Status Stock', audit_tkr::where('status', 'stock')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),               
            ];
        }

        // public static function canView(): bool
        // {
        //     return auth()->user()->role !== 'Marketing';
        // }
        
        // public static function canView(): bool
        // {
        //     return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
        // }
        

    }


