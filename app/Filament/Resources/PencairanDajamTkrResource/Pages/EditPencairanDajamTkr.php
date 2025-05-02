<?php

namespace App\Filament\Resources\PencairanDajamTkrResource\Pages;

use App\Filament\Resources\PencairanDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPencairanDajamTkr extends EditRecord
{
    protected static string $resource = PencairanDajamTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
