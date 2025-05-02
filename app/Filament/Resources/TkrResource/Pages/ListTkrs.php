<?php

namespace App\Filament\Resources\TkrResource\Pages;

use App\Filament\Resources\TkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTkrs extends ListRecords
{
    protected static string $resource = TkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
