<?php

namespace App\Filament\Resources\StokTkrResource\Pages;

use App\Filament\Resources\StokTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StokTkrResource\Widgets\StokTkrStats;


class ListStokTkrs extends ListRecords
{
    protected static string $resource = StokTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data TKR'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            StokTkrStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
