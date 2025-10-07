<?php

namespace App\Filament\Resources\GcvVerifikasiDajamResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\gcv_verifikasi_dajam;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Facades\Filament;

class GcvVerifikasiDajamStats extends BaseWidget
{
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Verifikasi Dajam GCV';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        $query = gcv_verifikasi_dajam::query();

        if ($tenant) {
            $query->where('team_id', $tenant->id);
        }

        return [
            Card::make('Total Data Dajam', $query->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('Total Site Plan', $query->distinct('siteplan')->count('siteplan'))
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('BTN Cikarang', (clone $query)->where('bank', 'BTN Cikarang')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('BTN Bekasi', (clone $query)->where('bank', 'btn_bekasi')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('BTN Karawang', (clone $query)->where('bank', 'btn_karawang')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('BJB Syariah', (clone $query)->where('bank', 'bjb_syariah')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('BJB Jababeka', (clone $query)->where('bank', 'bjb_jababeka')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('BTN Syariah', (clone $query)->where('bank', 'btn_syariah')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('BRI Bekasi', (clone $query)->where('bank', 'brii_bekasi')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('Sudah Diajukan', (clone $query)->where('status_dajam', 'sudah_diajukan')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),

            Card::make('Belum Diajukan', (clone $query)->where('status_dajam', 'belum_diajukan')->count())
                ->extraAttributes(['style' => 'background-color: #ffff; border-color: #234C63;']),
        ];
    }
}
