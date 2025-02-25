<?php

namespace App\Filament\Resources\AuditResource\Pages;

use App\Filament\Resources\AuditResource;
use Filament\Actions;
use Filament\Actions\Modal\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateAudit extends CreateRecord
{
    protected static string $resource = AuditResource::class;
    protected static ?string $title = "Buat Data Audit";
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
        ->label('Buat Data Audit');
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()
        ->label('Buat dan buat Data Audit lagi')
        ->color('Warning');
    }
    
    protected function getCancelFormAction() : Actions\Action
    {
        return parent::getCancelFormAction()
        ->label('Batal')
        ->color('danger');
    }
}
