<?php

namespace App\Filament\Resources\GcvKprResource\Pages;

use App\Filament\Resources\GcvKprResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvKprs extends ListRecords
{
    protected static string $resource = GcvKprResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
