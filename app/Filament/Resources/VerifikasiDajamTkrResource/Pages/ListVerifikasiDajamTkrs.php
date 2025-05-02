<?php

namespace App\Filament\Resources\VerifikasiDajamTkrResource\Pages;

use App\Filament\Resources\VerifikasiDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiDajamTkrs extends ListRecords
{
    protected static string $resource = VerifikasiDajamTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
