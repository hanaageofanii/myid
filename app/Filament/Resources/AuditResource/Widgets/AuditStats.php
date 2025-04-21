<?php

namespace App\Filament\Resources\AuditResource\Widgets;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Audit;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Support\Colors\Color;
class AuditStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Audit';
    protected function getStats(): array
    {
            return [
                Card::make('Total Data Audit', Audit::count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),    
                Card::make('Total Site Plan', Audit::distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),            
                Card::make('Terbangun', Audit::where('terbangun', 1)->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),
                Card::make('Belum Terbangun', Audit::where('terbangun', 0)->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),
                Card::make('Status Akad', Audit::where('status', 'akad')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),            
                Card::make('Status Belum Akad', Audit::whereNotIn('status', ['Stock', 'Akad'])
                ->orWhereNull('status')
                ->orWhere('status', '')
                ->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),
                Card::make('Status Stock', Audit::where('status', 'stock')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),               
            ];
        }
    }

