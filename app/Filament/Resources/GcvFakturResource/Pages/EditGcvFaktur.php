<?php

namespace App\Filament\Resources\GcvFakturResource\Pages;

use App\Filament\Resources\GcvFakturResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvFaktur extends EditRecord
{
    protected static string $resource = GcvFakturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
