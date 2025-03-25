<?php

namespace App\Filament\Resources\PencairanDajamResource\Pages;

use App\Filament\Resources\PencairanDajamResource;
use App\Filament\Resources\PencairanDajamResource\Widgets\pencairanDajamStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPencairanDajams extends ListRecords
{
    protected static string $resource = PencairanDajamResource::class;

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
            pencairanDajamStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
