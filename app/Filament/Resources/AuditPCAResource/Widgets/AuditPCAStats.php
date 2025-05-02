<?php

namespace App\Filament\Resources\AuditPCAResource\Widgets;

use App\Models\AuditPCA;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Support\Colors\Color;

class AuditPCAStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Audit PCA';
    
    protected function getStats(): array
    {
            return [
                Card::make('Total Data Audit', AuditPCA::count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),    
                Card::make('Total Site Plan', AuditPCA::distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),            
                Card::make('Terbangun', AuditPCA::where('terbangun', 1)->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Belum Terbangun', AuditPCA::where('terbangun', 0)->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Status Akad', AuditPCA::where('status', 'akad')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),            
                Card::make('Status Belum Akad', AuditPCA::whereNotIn('status', ['Stock', 'Akad'])
                ->orWhereNull('status')
                ->orWhere('status', '')
                ->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Status Stock', AuditPCA::where('status', 'stock')->count())
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

