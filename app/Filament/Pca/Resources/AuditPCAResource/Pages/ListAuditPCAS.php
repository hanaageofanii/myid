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
            Actions\CreateAction::make()
            ->label('Buat Data Audit GCV'),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         AuditStats::class,
    //     ];
    // }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
