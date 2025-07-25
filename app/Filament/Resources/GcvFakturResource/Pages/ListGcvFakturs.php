<?php

namespace App\Filament\Resources\GcvFakturResource\Pages;

use App\Filament\Resources\GcvFakturResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvFakturs extends ListRecords
{
    protected static string $resource = GcvFakturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}