<?php

namespace App\Filament\Resources\PajakTkrResource\Widgets;

use App\Models\PajakTkr as ModelsPajakTkr;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class PajakTkr extends BaseWidget
{
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Validasi';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Validasi', ModelsPajakTkr::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Total Site Plan', ModelsPajakTkr::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),  
            Card::make('Jumlah Validasi Unit Standar', ModelsPajakTkr::where('kavling', 'standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),            
            Card::make('Jumlah Validasi Unit Khusus', ModelsPajakTkr::where('kavling', 'khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Hook', ModelsPajakTkr::where('kavling', 'hook')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff;form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Komersil', ModelsPajakTkr::where('kavling', 'komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Tanah Lebih', ModelsPajakTkr::where('kavling', 'tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Kios', ModelsPajakTkr::where('kavling', 'kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 1%', ModelsPajakTkr::where('tarif_pph', '1%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 2.5%', ModelsPajakTkr::where('tarif_pph', '2.5%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}
