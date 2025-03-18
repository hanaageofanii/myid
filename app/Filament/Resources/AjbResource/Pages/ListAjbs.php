<?php

namespace App\Filament\Resources\AjbResource\Pages;

use App\Filament\Resources\AjbResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAjbs extends ListRecords
{
    protected static string $resource = AjbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
