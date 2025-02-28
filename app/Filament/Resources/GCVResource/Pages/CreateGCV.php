<?php

namespace App\Filament\Resources\GCVResource\Pages;

use App\Filament\Resources\GCVResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGCV extends CreateRecord
{
    protected static string $resource = GCVResource::class;
    protected static ?string $title = "Buat Data GCV";
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
        ->label('Tambah Data');
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()
        ->label('Tambah Data Lagi')
        ->color('warning');
    }
    
    protected function getCancelFormAction() : Actions\Action
    {
        return parent::getCancelFormAction()
        ->label('Batal')
        ->color('danger');
    }
}

