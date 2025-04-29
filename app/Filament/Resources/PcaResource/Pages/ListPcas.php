<?php

namespace App\Filament\Resources\PcaResource\Pages;

use App\Filament\Resources\PcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPcas extends ListRecords
{
    protected static string $resource = PcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
