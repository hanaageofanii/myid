<?php

namespace App\Filament\Resources\RekonsilResource\Pages;

use App\Filament\Resources\RekonsilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekonsil extends EditRecord
{
    protected static string $resource = RekonsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
