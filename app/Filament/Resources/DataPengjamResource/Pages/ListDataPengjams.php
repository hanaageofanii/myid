<?php

namespace App\Filament\Resources\DataPengjamResource\Pages;

use App\Filament\Resources\DataPengjamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataPengjams extends ListRecords
{
    protected static string $resource = DataPengjamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
