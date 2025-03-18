<?php

namespace App\Filament\Resources\PencairanAkadResource\Pages;

use App\Filament\Resources\PencairanAkadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPencairanAkads extends ListRecords
{
    protected static string $resource = PencairanAkadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
