<?php

namespace App\Filament\Resources\FormPajakPcaResource\Pages;

use App\Filament\Resources\FormPajakPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPajakPca extends EditRecord
{
    protected static string $resource = FormPajakPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
