<?php

namespace App\Filament\Resources\FormPencocokanResource\Pages;

use App\Filament\Resources\FormPencocokanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPencocokan extends EditRecord
{
    protected static string $resource = FormPencocokanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
