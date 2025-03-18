<?php

namespace App\Filament\Resources\PencairanDajamResource\Pages;

use App\Filament\Resources\PencairanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPencairanDajams extends ListRecords
{
    protected static string $resource = PencairanDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
