<?php

namespace App\Filament\Resources\GcvVerifikasiDajamResource\Pages;

use App\Filament\Resources\GcvVerifikasiDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvVerifikasiDajamResource\Widgets\GcvVerifikasiDajamStats;


class ListGcvVerifikasiDajams extends ListRecords
{
    protected static string $resource = GcvVerifikasiDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Verifikasi Dajam'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            GcvVerifikasiDajamStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
