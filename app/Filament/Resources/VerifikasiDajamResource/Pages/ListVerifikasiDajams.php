<?php

namespace App\Filament\Resources\VerifikasiDajamResource\Pages;

use App\Filament\Resources\VerifikasiDajamResource;
use App\Filament\Resources\VerifikasiDajamResource\Widgets\verifikasiDajamStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiDajams extends ListRecords
{
    protected static string $resource = VerifikasiDajamResource::class;

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
            verifikasiDajamStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

