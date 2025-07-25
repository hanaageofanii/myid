<?php

namespace App\Filament\Resources\GcvPencairanDajamResource\Pages;

use App\Filament\Resources\GcvPencairanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvPencairanDajamResource\Widgets\gcv_pencairan_dajamStats;


class ListGcvPencairanDajams extends ListRecords
{
    protected static string $resource = GcvPencairanDajamResource::class;

     protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Pencairan Dajam'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcv_pencairan_dajamStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
