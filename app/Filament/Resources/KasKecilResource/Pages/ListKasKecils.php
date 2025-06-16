<?php

namespace App\Filament\Resources\KasKecilResource\Pages;

use App\Filament\Resources\KasKecilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKasKecils extends ListRecords
{
    protected static string $resource = KasKecilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
