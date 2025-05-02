<?php

namespace App\Filament\Resources\LegalTkrResource\Pages;

use App\Filament\Resources\LegalTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegalTkrs extends ListRecords
{
    protected static string $resource = LegalTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
