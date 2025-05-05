<?php

namespace App\Filament\Resources\PencairanDajamTkrResource\Pages;

use App\Filament\Resources\PencairanDajamResource\Widgets\pencairanDajamTkrStats;
use App\Filament\Resources\PencairanDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PencairanDajamTkrResource\Widgets\PencairanDajamTkr;


class ListPencairanDajamTkrs extends ListRecords
{
    protected static string $resource = PencairanDajamTkrResource::class;

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
            pencairanDajamTkrStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
