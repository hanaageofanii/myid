<?php

namespace App\Filament\Resources\GcvPencairanDajamResource\Pages;

use App\Filament\Resources\GcvPencairanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvPencairanDajams extends ListRecords
{
    protected static string $resource = GcvPencairanDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
