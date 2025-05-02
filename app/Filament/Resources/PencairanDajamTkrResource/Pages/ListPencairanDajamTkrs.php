<?php

namespace App\Filament\Resources\PencairanDajamTkrResource\Pages;

use App\Filament\Resources\PencairanDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPencairanDajamTkrs extends ListRecords
{
    protected static string $resource = PencairanDajamTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
