<?php

namespace App\Filament\Resources\DajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\dajam;



class dajamStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Dajam', dajam::count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', dajam::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),      
            Card::make('BTN Cikarang', dajam::where('bank', 'BTN Cikarang')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),

            Card::make('BTN Bekasi', dajam::where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', dajam::where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', dajam::where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', dajam::where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', dajam::where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', dajam::where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #FFC85B; border-color: #234C63;'
                ]),  
        ];
    }
}
