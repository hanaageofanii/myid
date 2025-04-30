<?php

namespace App\Filament\Resources\PencairanAkadPcaResource\Pages;

use App\Filament\Resources\PencairanAkadPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreatePencairanAkadPca extends CreateRecord
{
    protected static string $resource = PencairanAkadPcaResource::class;
    protected static ?string $title = "Buat Data Pencairan Akad";
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

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Pencairan Akad Disimpan')
            ->body('Data Pencairan Akad telah berhasil disimpan.');
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}


