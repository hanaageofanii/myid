<?php

namespace App\Filament\Resources\StokTkrResource\Pages;

use App\Filament\Resources\StokTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStokTkr extends EditRecord
{
    protected static string $resource = StokTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
