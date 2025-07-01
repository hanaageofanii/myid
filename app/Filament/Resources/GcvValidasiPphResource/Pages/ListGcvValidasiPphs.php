<?php

namespace App\Filament\Resources\GcvValidasiPphResource\Pages;

use App\Filament\Resources\GcvValidasiPphResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvValidasiPphs extends ListRecords
{
    protected static string $resource = GcvValidasiPphResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
