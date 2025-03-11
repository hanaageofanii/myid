<?php

namespace App\Filament\Resources\FormPajakResource\Pages;

use App\Filament\Resources\FormPajakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPajak extends EditRecord
{
    protected static string $resource = FormPajakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
