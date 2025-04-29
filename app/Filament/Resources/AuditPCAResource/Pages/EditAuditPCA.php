<?php

namespace App\Filament\Resources\AuditPCAResource\Pages;

use App\Filament\Resources\AuditPCAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditPCA extends EditRecord
{
    protected static string $resource = AuditPCAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
