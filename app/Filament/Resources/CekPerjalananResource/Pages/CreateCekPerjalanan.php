<?php

namespace App\Filament\Resources\CekPerjalananResource\Pages;

use App\Filament\Resources\CekPerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreateCekPerjalanan extends CreateRecord
{
    protected static string $resource = CekPerjalananResource::class;
    protected static ?string $title = "Buat Cek Rekening & Transaksi Internal";
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
            ->title('Data Cek Rekening & Transaksi Internal Disimpan')
            ->body('Data Cek Rekening & Transaksi Internal telah berhasil disimpan.');
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}





