<?php

namespace App\Filament\Resources\PajakTkrResource\Pages;

use App\Filament\Resources\PajakTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPajakTkr extends EditRecord
{
    protected static string $resource = PajakTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
