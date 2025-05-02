<?php

namespace App\Filament\Resources\FormKprTkrResource\Widgets;

use App\Models\FormKprTkr as ModelsFormKprTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class FormKprTkr extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard KPR TKR';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Penjualan KPR', ModelsFormKprTkr::count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),    
            Card::make('Total Site Plan', ModelsFormKprTkr::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),      
            Card::make('Status Akad', ModelsFormKprTkr::where('status_akad', 'akad')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),            
            Card::make('Status Batal Akad', ModelsFormKprTkr::where('status_akad','batal')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),   
            Card::make('Total Unit Standar', ModelsFormKprTkr::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Standar', ModelsFormKprTkr::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Standar', ModelsFormKprTkr::where('jenis_unit','standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Khusus', ModelsFormKprTkr::where('jenis_unit','khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Hook', ModelsFormKprTkr::where('jenis_unit','hook')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Komersil', ModelsFormKprTkr::where('jenis_unit','komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Tanah Lebih', ModelsFormKprTkr::where('jenis_unit','tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  
            Card::make('Total Unit Kios', ModelsFormKprTkr::where('jenis_unit','kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; border-color: #234C63;'
            ]),  

        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}


