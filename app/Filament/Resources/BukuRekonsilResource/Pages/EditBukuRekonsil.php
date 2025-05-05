<?php

namespace App\Filament\Resources\BukuRekonsilResource\Pages;

use App\Filament\Resources\BukuRekonsilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBukuRekonsil extends EditRecord
{
    protected static string $resource = BukuRekonsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
