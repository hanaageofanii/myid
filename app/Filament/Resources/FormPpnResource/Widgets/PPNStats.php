<?php

namespace App\Filament\Resources\FormPpnResource\Widgets;

use App\Models\form_ppn;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class PPNStats extends BaseWidget
{
    protected static ?int $sort = 6;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Faktur GCV';
    protected function getStats(): array
    {
        return [
            Card::make('Total Data Faktur', form_ppn::count())
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]), 
            Card::make('Total Site Plan', form_ppn::distinct('siteplan')->count('siteplan'))
            ->extraAttributes([
                'style' => 'background-color: #ffff; border-color: #234C63;'
            ]),  
            Card::make('Jumlah Faktur Unit Standar', form_ppn::where('kavling', 'standar')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),            
            Card::make('Jumlah Faktur Unit Khusus', form_ppn::where('kavling', 'khusus')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Hook', form_ppn::where('kavling', 'hook')->count())
            ->extraAttributes([
                'style' => 'background-color: #ffff;form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Komersil', form_ppn::where('kavling', 'komersil')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Tanah Lebih', form_ppn::where('kavling', 'tanah_lebih')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Faktur Unit Kios', form_ppn::where('kavling', 'kios')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 11%', form_ppn::where('tarif_ppn', '11%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Validasi Tarif 12%', form_ppn::where('tarif_ppn', '12%')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status DTP', form_ppn::where('status_ppn', 'dtp')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status DTP Sebagian', form_ppn::where('status_ppn', 'dtp_sebagian')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status Dibebaskan', form_ppn::where('status_ppn', 'dibebaskan')->count())
            ->extraAttributes([
                'style' => 'background-color:#ffff; form_pajak; border-color: #234C63;'
            ]),
            Card::make('Jumlah Status Bayar', form_ppn::where('status_ppn', 'bayar')->count())
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
