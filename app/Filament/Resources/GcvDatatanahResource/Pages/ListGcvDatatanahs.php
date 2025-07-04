<?php

namespace App\Filament\Resources\GcvDatatanahResource\Pages;

use App\Filament\Resources\GcvDatatanahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvDatatanahs extends ListRecords
{
    protected static string $resource = GcvDatatanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
