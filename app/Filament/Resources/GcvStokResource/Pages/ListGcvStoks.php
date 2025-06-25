<?php

namespace App\Filament\Resources\GcvStokResource\Pages;

use App\Filament\Resources\GcvStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvStoks extends ListRecords
{
    protected static string $resource = GcvStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}