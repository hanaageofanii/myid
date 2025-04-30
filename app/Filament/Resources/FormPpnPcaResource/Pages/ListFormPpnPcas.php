<?php

namespace App\Filament\Resources\FormPpnPcaResource\Pages;

use App\Filament\Resources\FormPpnPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormPpnPcas extends ListRecords
{
    protected static string $resource = FormPpnPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
