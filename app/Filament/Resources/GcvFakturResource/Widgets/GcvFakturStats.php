<?php

namespace App\Filament\Resources\GcvFakturResource\Widgets;

use App\Models\gcv_faktur;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class GcvFakturStats extends BaseWidget
{
     protected static ?int $sort = 6;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Faktur TKR';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Faktur', gcv_faktur::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Total Site Plan', gcv_faktur::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Standar', gcv_faktur::where('kavling', 'standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Khusus', gcv_faktur::where('kavling', 'khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Hook', gcv_faktur::where('kavling', 'hook')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff;form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Komersil', gcv_faktur::where('kavling', 'komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Tanah Lebih', gcv_faktur::where('kavling', 'tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Kios', gcv_faktur::where('kavling', 'kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 11%', gcv_faktur::where('tarif_ppn', '11%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 12%', gcv_faktur::where('tarif_ppn', '12%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status DTP', gcv_faktur::where('status_ppn', 'dtp')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status DTP Sebagian', gcv_faktur::where('status_ppn', 'dtp_sebagian')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status Dibebaskan', gcv_faktur::where('status_ppn', 'dibebaskan')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status Bayar', gcv_faktur::where('status_ppn', 'bayar')->count())
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