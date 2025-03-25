<?php

namespace App\Filament\Resources\VerifikasiDajamResource\Pages;

use App\Filament\Resources\VerifikasiDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiDajams extends ListRecords
{
    protected static string $resource = VerifikasiDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
