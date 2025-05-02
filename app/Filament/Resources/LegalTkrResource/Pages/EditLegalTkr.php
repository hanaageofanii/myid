<?php

namespace App\Filament\Resources\LegalTkrResource\Pages;

use App\Filament\Resources\LegalTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegalTkr extends EditRecord
{
    protected static string $resource = LegalTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
