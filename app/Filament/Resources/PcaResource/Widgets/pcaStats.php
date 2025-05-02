<?php

namespace App\Filament\Resources\PcaResource\Widgets;

use App\Models\pca;
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
            Card::make('Total Data PCA', pca::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),               
            Card::make('Total PCA', pca::where('proyek', 'PCA')->count())
            ->color('white') 
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63; color: #234C63; font-weight: bold;'
            ]),

            Card::make('Total PCA Cira', pca::where('proyek', 'PCA_cira')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
            Card::make('Total Terbooking', pca::where('status', 'booking')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),  
            Card::make('Total Akad', pca::where('kpr_status', 'akad')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]), 
            Card::make('Total SP3K', pca::where('kpr_status', 'sp3k')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]), 
            Card::make('Total Batal Akad', pca::where('kpr_status', 'batal')->count())
                ->extraAttributes([
                    'style' => 'background-color:#ffff ; border-color: #234C63;'
                ]), 
                Card::make('Total Unit Standar (PCA)', pca::where('kavling', 'standar')->where('proyek', 'PCA')->count()) 
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Total Unit Standar (PCA Cira)', pca::where('kavling', 'standar')->where('proyek', 'PCA_cira')->count()) 
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                Card::make('Total Unit Khusus', pca::where('kavling','khusus')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),  
                Card::make('Total Unit Hook', pca::where('kavling','hook')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),  
                Card::make('Total Unit Tanah Lebih', pca::where('kavling','tanah_lebih')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),  
                Card::make('Total Unit Kios', pca::where('kavling','kios')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),  
                Card::make('Total Unit Komersil (PCA)', pca::where('kavling', 'komersil')->where('proyek', 'PCA')->count()) 
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

                Card::make('Total Unit Komersil (PCA Cira)', pca::where('kavling', 'komersil')->where('proyek', 'PCA_cira')->count()) 
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

        ];
    }
    // public static function canView(): bool
    // {
    //     return in_array(auth()->user()->role, ['Marketing']);
    // }

}
