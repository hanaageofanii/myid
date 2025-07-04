<?php

namespace App\Filament\Resources\GcvDatatandaterimaResource\Pages;

use App\Filament\Resources\GcvDatatandaterimaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreateGcvDatatandaterima extends CreateRecord
{
    protected static string $resource = GcvDatatandaterimaResource::class;
    protected static ?string $title = "Buat Data Tanda Terima";
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
        ->label('Tambah Data Tanda Terima');
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
            ->title('Data Tanda Terima Disimpan')
            ->body('Data Tanda Terima telah berhasil disimpan.');
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}