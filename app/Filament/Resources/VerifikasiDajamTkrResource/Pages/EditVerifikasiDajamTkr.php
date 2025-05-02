<?php

namespace App\Filament\Resources\VerifikasiDajamTkrResource\Pages;

use App\Filament\Resources\VerifikasiDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiDajamTkr extends EditRecord
{
    protected static string $resource = VerifikasiDajamTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
