<?php

namespace App\Filament\Resources\FormPencocokanResource\Pages;

use App\Filament\Resources\FormPencocokanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormPencocokans extends ListRecords
{
    protected static string $resource = FormPencocokanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
