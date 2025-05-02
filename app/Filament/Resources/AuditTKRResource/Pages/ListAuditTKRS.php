<?php

namespace App\Filament\Resources\AuditTkrResource\Pages;

use App\Filament\Resources\AuditTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AuditTkrResource\Widgets\audit_tkrStats;


class ListAuditTkrs extends ListRecords
{
    protected static string $resource = AuditTkrResource::class;

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
            audit_tkrStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
