<?php

namespace App\Filament\Resources\GcvVerifikasiDajamResource\Pages;

use App\Filament\Resources\GcvVerifikasiDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvVerifikasiDajam extends EditRecord
{
    protected static string $resource = GcvVerifikasiDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
