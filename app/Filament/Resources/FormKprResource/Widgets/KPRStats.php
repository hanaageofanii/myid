<?php

namespace App\Filament\Resources\FormKprResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\form_kpr;



class KPRStats extends BaseWidget
{

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard KPR';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Penjualan KPR', form_kpr::count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', form_kpr::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),      
            Card::make('Status Akad', form_kpr::where('status_akad', 'akad')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),            
            Card::make('Status Batal Akad', form_kpr::where('status_akad','batal')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),   
            Card::make('Total Unit Standar', form_kpr::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Standar', form_kpr::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Standar', form_kpr::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Khusus', form_kpr::where('jenis_unit','khusus')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Hook', form_kpr::where('jenis_unit','hook')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Komersil', form_kpr::where('jenis_unit','komersil')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Tanah Lebih', form_kpr::where('jenis_unit','tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Kios', form_kpr::where('jenis_unit','kios')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFB8E0; border-color: #234C63;'
            ]),  

        ];
    }
}


