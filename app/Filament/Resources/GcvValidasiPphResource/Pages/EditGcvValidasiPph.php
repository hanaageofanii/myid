<?php

namespace App\Filament\Resources\GcvValidasiPphResource\Pages;

use App\Filament\Resources\GcvValidasiPphResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvValidasiPph extends EditRecord
{
    protected static string $resource = GcvValidasiPphResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
