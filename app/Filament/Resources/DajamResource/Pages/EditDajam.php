<?php

namespace App\Filament\Resources\DajamResource\Pages;

use App\Filament\Resources\DajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDajam extends EditRecord
{
    protected static string $resource = DajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
