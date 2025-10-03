<?php

namespace App\Filament\Resources\GcvMasterDajamResource\Pages;

use App\Filament\Resources\GcvMasterDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvMasterDajams extends ListRecords
{
    protected static string $resource = GcvMasterDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
