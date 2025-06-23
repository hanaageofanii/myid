<?php

namespace App\Filament\Resources\GcvDatatandaterimaResource\Pages;

use App\Filament\Resources\GcvDatatandaterimaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvDatatandaterimas extends ListRecords
{
    protected static string $resource = GcvDatatandaterimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
