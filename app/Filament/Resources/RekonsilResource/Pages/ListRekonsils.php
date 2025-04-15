<?php

namespace App\Filament\Resources\RekonsilResource\Pages;

use App\Filament\Resources\RekonsilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekonsils extends ListRecords
{
    protected static string $resource = RekonsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
