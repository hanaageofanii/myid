<?php

namespace App\Filament\Resources\FormPpnResource\Pages;

use App\Filament\Resources\FormPpnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPpn extends EditRecord
{
    protected static string $resource = FormPpnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
