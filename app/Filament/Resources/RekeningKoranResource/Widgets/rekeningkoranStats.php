<?php


namespace App\Filament\Resources\RekeningKoranResource\Widgets;

use App\Models\rekening_koran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;


class rekeningkoranStats extends BaseWidget
{
    protected function getCards(): array
    {
        $all = rekening_koran::all();

        return [
            Card::make('Total Transaksi', $all->count()),

            Card::make('Total Debit', 'Rp ' . number_format(
                $all->where('tipe', 'debit')->sum('nominal'), 0, ',', '.'
            )),

            Card::make('Total Kredit', 'Rp ' . number_format(
                $all->where('tipe', 'kredit')->sum('nominal'), 0, ',', '.'
            )),

            Card::make('Saldo Terakhir', 'Rp ' . number_format(
                $all->last()?->saldo ?? 0, 0, ',', '.'
            )),
        ];
    }
}