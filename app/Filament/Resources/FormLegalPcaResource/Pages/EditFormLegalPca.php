<?php

namespace App\Filament\Resources\FormLegalPcaResource\Pages;

use App\Filament\Resources\FormLegalPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormLegalPca extends EditRecord
{
    protected static string $resource = FormLegalPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
