<?php

namespace App\Filament\Resources\PengajuanDajamPcaResource\Pages;

use App\Filament\Resources\PengajuanDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreatePengajuanDajamPca extends CreateRecord
{
    protected static string $resource = PengajuanDajamPcaResource::class;
    protected static ?string $title = "Buat Pengajuan Dajam";
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
            ->title('Pengajuan Dajam Disimpan')
            ->body('Pengajuan Dajam telah berhasil disimpan.');
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}



