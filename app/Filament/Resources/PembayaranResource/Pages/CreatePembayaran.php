<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use App\Filament\Resources\PembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected static ?string $title = "Buat Data Pembayaran";
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
            ->title('Data Pembayaran')
            ->body('Data Pembayaran.');
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}



