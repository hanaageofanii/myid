<?php

namespace App\Filament\Resources\GCVResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\GCV;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Support\Colors\Color;

class GCVStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard GCV';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data GCV & GCV Cira', GCV::count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
                
            Card::make('Total GCV', GCV::where('proyek', 'gcv')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63; color: #234C63; font-weight: bold;'
                ]),
    
            Card::make('Total GCV Cira', GCV::where('proyek', 'gcv_cira')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63; color: #234C63; font-weight: bold;'
                ]),

// ===== GCV =====
// Card::make('Total GCV', GCV::where('proyek', 'gcv')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63; color: #234C63; font-weight: bold;'
//     ]),
// Card::make(
//     'Booking Belum Akad (GCV)', 
//     GCV::where('status', 'booking')
//         ->where('proyek', 'gcv')
//         ->where(function ($query) {
//             $query->whereNull('kpr_status')
//                  ->orWhereNotIn('kpr_status', ['akad', 'batal', 'sp3k']);
//         })
//         ->count()
// )
// ->extraAttributes([
//     'style' => 'background-color: #ffff; border-color: #234C63;'
// ]),

    
// Card::make('Belum Terbooking (GCV)', GCV::whereNULL('status' )->where('proyek', 'gcv')->count())
// ->extraAttributes([
//     'style' => 'background-color: #ffff; border-color: #234C63;']),
    
// Card::make('Akad (GCV)', GCV::where('kpr_status', 'akad')->where('proyek', 'gcv')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('SP3K (GCV)', GCV::where('kpr_status', 'sp3k')->where('proyek', 'gcv')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Batal Akad (GCV)', GCV::where('kpr_status', 'batal')->where('proyek', 'gcv')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Total Unit Standar (GCV)', GCV::where('kavling', 'standar')->where('proyek', 'gcv')->count()) 
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Total Unit Komersil (GCV)', GCV::where('kavling', 'komersil')->where('proyek', 'gcv')->count()) 
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),

// // ===== GCV CIRA =====
// Card::make('Total GCV Cira', GCV::where('proyek', 'gcv_cira')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
    
// Card::make(
//     'Booking Belum Akad (GCV Cira)', 
//     GCV::where('status', 'booking')
//         ->where('proyek', 'gcv_cira')
//         ->where(function ($query) {
//             $query->whereNull('kpr_status')
//                   ->orWhereNotIn('kpr_status', ['akad', 'batal', 'sp3k']);
//         })
//         ->count()
// )
// ->extraAttributes([
//     'style' => 'background-color: #ffff; border-color: #234C63;'
// ]),

// Card::make('Belum Terbooking (GCV Cira)', GCV::whereNULL('status' )->where('proyek', 'gcv_cira')->count())
// ->extraAttributes([
//     'style' => 'background-color: #ffff; border-color: #234C63;']),

// Card::make('Akad (GCV Cira)', GCV::where('kpr_status', 'akad')->where('proyek', 'gcv_cira')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('SP3K (GCV Cira)', GCV::where('kpr_status', 'sp3k')->where('proyek', 'gcv_cira')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Batal Akad (GCV Cira)', GCV::where('kpr_status', 'batal')->where('proyek', 'gcv_cira')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Total Unit Standar (GCV Cira)', GCV::where('kavling', 'standar')->where('proyek', 'gcv_cira')->count()) 
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Total Unit Komersil (GCV Cira)', GCV::where('kavling', 'komersil')->where('proyek', 'gcv_cira')->count()) 
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),

// // ===== LAINNYA (UMUM) =====
// Card::make('Total Blok Hook', GCV::where('kavling','khusus')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Total Kavlingan Hook', GCV::where('kavling','hook')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Total Unit Tanah Lebih', GCV::where('kavling','tanah_lebih')->count())
//     ->extraAttributes([
//         'style' => 'background-color: #ffff; border-color: #234C63;'
//     ]),
// Card::make('Total Unit Kios', GCV::where('kavling','kios')->count())
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
