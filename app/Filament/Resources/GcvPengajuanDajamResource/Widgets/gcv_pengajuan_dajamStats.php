<?php

namespace App\Filament\Resources\GcvPengajuanDajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\gcv_pengajuan_dajam;
use Filament\Facades\Filament;

class gcv_pengajuan_dajamStats extends BaseWidget
{
    protected static ?int $sort = 12;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Pengajuan Dajam GCV';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_pengajuan_dajam::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Pengajuan Dajam', $query->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Cikarang', (clone $query)->where('bank', 'BTN Cikarang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Bekasi', (clone $query)->where('bank', 'btn_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Karawang', (clone $query)->where('bank', 'btn_karawang')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Syariah', (clone $query)->where('bank', 'bjb_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BJB Jababeka', (clone $query)->where('bank', 'bjb_jababeka')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BTN Syariah', (clone $query)->where('bank', 'btn_syariah')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('BRI Bekasi', (clone $query)->where('bank', 'brii_bekasi')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam Sertifikat', (clone $query)->where('nama_dajam', 'sertifikat')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam IMB', (clone $query)->where('nama_dajam', 'imb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam Listrik', (clone $query)->where('nama_dajam', 'listrik')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam Bestek', (clone $query)->where('nama_dajam', 'bestek')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam JKK', (clone $query)->where('nama_dajam', 'jkk')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam PPH', (clone $query)->where('nama_dajam', 'pph')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),

            Card::make('Dajam BPHTB', (clone $query)->where('nama_dajam', 'bphtb')->count())
                ->extraAttributes([
                    'style' => 'background-color: #ffff; border-color: #234C63;'
                ]),
        ];
    }
}
