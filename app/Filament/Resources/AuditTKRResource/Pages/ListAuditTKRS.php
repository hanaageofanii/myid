<?php

namespace App\Filament\Resources\AuditTKRResource\Pages;

use App\Filament\Resources\AuditTKRResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AuditTKRResource\Widgets\AuditTKR;


class ListAuditTKRS extends ListRecords
{
    protected static string $resource = AuditTKRResource::class;

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
            AuditTKR::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
