<?php

namespace App\Filament\Resources\VerifikasiDajamResource\Pages;

use App\Filament\Resources\VerifikasiDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiDajam extends EditRecord
{
    protected static string $resource = VerifikasiDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
