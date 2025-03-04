<?php

namespace App\Filament\Resources\FormKprResource\Pages;

use App\Filament\Resources\FormKprResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFormKpr extends CreateRecord
{
    protected static string $resource = FormKprResource::class;
    protected static ?string $title = "Buat Data KPR";
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
        ->label('Tambah Data KPR');
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