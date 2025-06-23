<?php

namespace App\Filament\Resources\GcvDatatandaterimaResource\Pages;

use App\Filament\Resources\GcvDatatandaterimaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvDatatandaterima extends EditRecord
{
    protected static string $resource = GcvDatatandaterimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
