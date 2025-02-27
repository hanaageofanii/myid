<?php

namespace App\Filament\Resources\GCVResource\Pages;

use App\Filament\Resources\GCVResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGCVS extends ListRecords
{
    protected static string $resource = GCVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Event'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
        ];
    }
}
