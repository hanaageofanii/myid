<?php

namespace App\Filament\Pca\Resources\AuditPCAResource\Pages;

use App\Filament\Pca\Resources\AuditPCAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuditPCAS extends ListRecords
{
    protected static string $resource = AuditPCAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
