<?php

namespace App\Filament\Resources\FormLegalPcaResource\Pages;

use App\Filament\Resources\FormLegalPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormLegalPcas extends ListRecords
{
    protected static string $resource = FormLegalPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
