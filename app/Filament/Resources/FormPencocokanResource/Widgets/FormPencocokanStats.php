<?php

namespace App\Filament\Resources\FormPencocokanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\form_pencocokan;
use Filament\Widgets\StatsOverviewWidget\Card;

use Illuminate\Support\Carbon;

class FormPencocokanStats extends BaseWidget
{

    protected static ?int $sort = 18;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Pencocokan';

    protected function getCards(): array
    {
        return [
            Card::make('Total Transaksi', number_format(form_pencocokan::count()))
                ->description('Semua transaksi yang telah masuk')
                ->color('primary'),

            Card::make('Total Jumlah (Rp)', 'Rp. ' . number_format(form_pencocokan::sum('jumlah')))
                ->description('Jumlah uang dari semua transaksi')
                ->color('success'),

            Card::make('Transaksi Bermasalah', number_format(form_pencocokan::where('nominal_selisih', '!=', 0)->count()))
                ->description('Transaksi dengan selisih')
                ->color('danger'),

            Card::make('Transaksi Disetujui', number_format(form_pencocokan::where('status', 'approved')->count()))
                ->description('Jumlah transaksi yang disetujui')
                ->color('success'),

            Card::make('Transaksi dengan Tindakan', number_format(form_pencocokan::whereNotNull('tindakan')->count()))
                ->description('Jumlah transaksi dengan tindakan')
                ->color('warning'),

            Card::make('Transaksi Tervalidasi', number_format(form_pencocokan::whereNotNull('tanggal_validasi')->count()))
                ->description('Jumlah transaksi yang telah tervalidasi')
                ->color('info'),

            Card::make('Transaksi dengan Selisih Nominal', number_format(form_pencocokan::where('nominal_selisih', '!=', 0)->count()))
                ->description('Transaksi yang memiliki selisih nominal')
                ->color('danger'),
        ];
    }
}
