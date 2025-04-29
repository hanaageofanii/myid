<?php

namespace App\Filament\Resources\AuditPCAResource\Pages;

use App\Filament\Resources\AuditPCAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AuditPCAResource\Widgets\AuditPCAStats;


class ListAuditPCAS extends ListRecords
{
    protected static string $resource = AuditPCAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Audit PCA'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AuditPCAStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
