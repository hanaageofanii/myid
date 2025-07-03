<?php

namespace App\Filament\Resources\GcvVerifikasiDajamResource\Pages;

use App\Filament\Resources\GcvVerifikasiDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvVerifikasiDajams extends ListRecords
{
    protected static string $resource = GcvVerifikasiDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
