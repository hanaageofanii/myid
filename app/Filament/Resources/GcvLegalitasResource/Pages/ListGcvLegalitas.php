<?php

namespace App\Filament\Resources\GcvLegalitasResource\Pages;

use App\Filament\Resources\GcvLegalitasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvLegalitas extends ListRecords
{
    protected static string $resource = GcvLegalitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
