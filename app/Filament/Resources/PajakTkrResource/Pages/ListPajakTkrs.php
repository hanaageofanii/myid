<?php

namespace App\Filament\Resources\PajakTkrResource\Pages;

use App\Filament\Resources\PajakTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPajakTkrs extends ListRecords
{
    protected static string $resource = PajakTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
