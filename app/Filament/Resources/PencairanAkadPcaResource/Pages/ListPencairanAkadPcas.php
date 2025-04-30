<?php

namespace App\Filament\Resources\PencairanAkadPcaResource\Pages;

use App\Filament\Resources\PencairanAkadPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPencairanAkadPcas extends ListRecords
{
    protected static string $resource = PencairanAkadPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
