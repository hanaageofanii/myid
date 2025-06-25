<?php

namespace App\Filament\Resources\GcvStokResource\Pages;

use App\Filament\Resources\GcvStokResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvStok extends EditRecord
{
    protected static string $resource = GcvStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}