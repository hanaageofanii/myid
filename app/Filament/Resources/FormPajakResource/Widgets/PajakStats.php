<?php

namespace App\Filament\Resources\FormPajakResource\Widgets;

use App\Models\form_pajak;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Filament\Widgets\StatsOverviewWidget\Card;

class PajakStats extends BaseWidget
{

    
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Validasi';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Validasi', form_pajak::count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]), 
            Card::make('Total Site Plan', form_pajak::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #FFC85B; border-color: #234C63;'
            ]),  
            Card::make('Jumlah Validasi Unit Standar', form_pajak::where('kavling', 'standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#FFC85B; form_pajak; border-color: #234C63;'
            ]),            
            Card::make('Jumlah Validasi Unit Khusus', form_pajak::where('kavling', 'khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#FFC85B; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Hook', form_pajak::where('kavling', 'hook')->count())
            ->extraAttributes([
                'style' => 'background-color: #FFC85B;form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Komersil', form_pajak::where('kavling', 'komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#FFC85B; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Tanah Lebih', form_pajak::where('kavling', 'tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#FFC85B; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Unit Kios', form_pajak::where('kavling', 'kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#FFC85B; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 1%', form_pajak::where('tarif_pph', '1%')->count())
            ->extraAttributes([
                'style' => 'background-color:#FFC85B; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 2.5%', form_pajak::where('tarif_pph', '2.5%')->count())
            ->extraAttributes([
                'style' => 'background-color:#FFC85B; form_pajak; border-color: #234C63;'
            ]),
        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}
