<?php

namespace App\Filament\Resources\StokTkrResource\Widgets;

use App\Models\StokTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class StokTkrStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard TKR';
    protected function getStats(): array
    {
        return [
            
            Card::make('Total Data Tkr', StokTkr::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),   
            
            Card::make('Total Tkr', StokTkr::where('proyek', 'tkr')->count())
            ->color('white') 
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63; color: #234C63; font-weight: bold;'
            ]),
            
            Card::make('Total Tkr Cira', StokTkr::where('proyek', 'tkr_cira')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
          
            // Card::make('Total Terbooking', StokTkr::where('status', 'booking')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            // Card::make('Total Akad', StokTkr::where('kpr_status', 'akad')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]), 
            // Card::make('Total SP3K', StokTkr::where('kpr_status', 'sp3k')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]), 
            // Card::make('Total Batal Akad', StokTkr::where('kpr_status', 'batal')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color:#ffff ; border-color: #234C63;'
            //     ]), 
            //     Card::make('Total Unit Standar (Tkr)', StokTkr::where('kavling', 'standar')->where('proyek', 'StokTkr')->count()) 
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),
            //     Card::make('Total Unit Standar (Tkr Cira)', StokTkr::where('kavling', 'standar')->where('proyek', 'StokTkr_cira')->count()) 
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),
            //     Card::make('Total Unit Khusus', StokTkr::where('kavling','khusus')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Hook', StokTkr::where('kavling','hook')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Tanah Lebih', StokTkr::where('kavling','tanah_lebih')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Kios', StokTkr::where('kavling','kios')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Komersil (Tkr)', StokTkr::where('kavling', 'komersil')->where('proyek', 'StokTkr')->count()) 
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            //     Card::make('Total Unit Komersil (Tkr Cira)', StokTkr::where('kavling', 'komersil')->where('proyek', 'StokTkr_cira')->count()) 
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

        ];
    }
    // public static function canView(): bool
    // {
    //     return in_array(auth()->user()->role, ['Marketing']);
    // }

}
