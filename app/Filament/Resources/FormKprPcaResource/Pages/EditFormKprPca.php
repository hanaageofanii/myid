<?php

namespace App\Filament\Resources\FormKprPcaResource\Pages;

use App\Filament\Resources\FormKprPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormKprPca extends EditRecord
{
    protected static string $resource = FormKprPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
