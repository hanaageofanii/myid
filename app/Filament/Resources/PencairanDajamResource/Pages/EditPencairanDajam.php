<?php

namespace App\Filament\Resources\PencairanDajamResource\Pages;

use App\Filament\Resources\PencairanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPencairanDajam extends EditRecord
{
    protected static string $resource = PencairanDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
