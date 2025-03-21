<?php

namespace App\Filament\Resources\DajamResource\Pages;

use App\Filament\Resources\DajamResource;
use App\Filament\Resources\DajamResource\Widgets\dajamStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDajams extends ListRecords
{
    protected static string $resource = DajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            dajamStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

