<?php

namespace App\Filament\Resources\AjbResource\Pages;

use App\Filament\Resources\AjbResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAjb extends EditRecord
{
    protected static string $resource = AjbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
