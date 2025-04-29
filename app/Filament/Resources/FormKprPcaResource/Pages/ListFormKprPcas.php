<?php

namespace App\Filament\Resources\FormKprPcaResource\Pages;

use App\Filament\Resources\FormKprPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormKprPcas extends ListRecords
{
    protected static string $resource = FormKprPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
