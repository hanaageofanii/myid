<?php

namespace App\Filament\Resources\PcaResource\Widgets;

use App\Models\Pca;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class pcaStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard PCA';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data PCA', Pca::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),               
            // Card::make('Total PCA', Pca::where('proyek', 'PCA')->count())
            // ->color('white') 
            // ->extraAttributes([
            //     'style' => 'background-color: #ffff; border-color: #234C63; color: #234C63; font-weight: bold;'
            // ]),

            // Card::make('Total PCA Cira', Pca::where('proyek', 'PCA_cira')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),
            // Card::make('Total Terbooking', Pca::where('status', 'booking')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            // Card::make('Total Akad', Pca::where('kpr_status', 'akad')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]), 
            // Card::make('Total SP3K', Pca::where('kpr_status', 'sp3k')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]), 
            // Card::make('Total Batal Akad',Pca::where('kpr_status', 'batal')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color:#ffff ; border-color: #234C63;'
            //     ]), 
            //     Card::make('Total Unit Standar (PCA)', Pca::where('kavling', 'standar')->where('proyek', 'PCA')->count()) 
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),
            //     Card::make('Total Unit Standar (PCA Cira)', Pca::where('kavling', 'standar')->where('proyek', 'PCA_cira')->count()) 
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),
            //     Card::make('Total Unit Khusus', Pca::where('kavling','khusus')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Hook', Pca::where('kavling','hook')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Tanah Lebih', Pca::where('kavling','tanah_lebih')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Kios', Pca::where('kavling','kios')->count())
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),  
            //     Card::make('Total Unit Komersil (PCA)', Pca::where('kavling', 'komersil')->where('proyek', 'PCA')->count()) 
            //     ->extraAttributes([
            //         'style' => 'background-color: #ffff; border-color: #234C63;'
            //     ]),

            //     Card::make('Total Unit Komersil (PCA Cira)', Pca::where('kavling', 'komersil')->where('proyek', 'PCA_cira')->count()) 
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
