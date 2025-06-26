<?php

namespace App\Filament\Resources\GcvPencairanAkadResource\Pages;

use App\Filament\Resources\GcvPencairanAkadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvPencairanAkads extends ListRecords
{
    protected static string $resource = GcvPencairanAkadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
