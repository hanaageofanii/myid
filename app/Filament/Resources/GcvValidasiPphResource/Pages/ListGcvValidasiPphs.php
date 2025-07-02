<?php

namespace App\Filament\Resources\GcvValidasiPphResource\Pages;

use App\Filament\Resources\GcvValidasiPphResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvValidasiPphResource\Widgets\gcv_validasi_pphStats;


class ListGcvValidasiPphs extends ListRecords
{
    protected static string $resource = GcvValidasiPphResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Validasi PPH'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcv_validasi_pphStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
