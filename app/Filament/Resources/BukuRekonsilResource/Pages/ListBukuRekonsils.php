<?php

namespace App\Filament\Resources\BukuRekonsilResource\Pages;

use App\Filament\Resources\BukuRekonsilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBukuRekonsils extends ListRecords
{
    protected static string $resource = BukuRekonsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
