<?php

namespace App\Filament\Resources\FormPpnResource\Pages;

use App\Filament\Resources\FormPpnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormPpns extends ListRecords
{
    protected static string $resource = FormPpnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
