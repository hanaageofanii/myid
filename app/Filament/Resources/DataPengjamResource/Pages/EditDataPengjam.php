<?php

namespace App\Filament\Resources\DataPengjamResource\Pages;

use App\Filament\Resources\DataPengjamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataPengjam extends EditRecord
{
    protected static string $resource = DataPengjamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
