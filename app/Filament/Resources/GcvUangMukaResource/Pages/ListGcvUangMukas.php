<?php

namespace App\Filament\Resources\GcvUangMukaResource\Pages;

use App\Filament\Resources\GcvUangMukaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvUangMukas extends ListRecords
{
    protected static string $resource = GcvUangMukaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
