<?php

namespace App\Filament\Resources\FormLegalResource\Pages;

use App\Filament\Resources\FormLegalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormLegals extends ListRecords
{
    protected static string $resource = FormLegalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
