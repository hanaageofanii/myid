<?php

namespace App\Filament\Resources\GcvFakturResource\Pages;

use App\Filament\Resources\GcvFakturResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateGcvFaktur extends CreateRecord
{
    protected static string $resource = GcvFakturResource::class;
    protected static ?string $title = "Buat Data Faktur";
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
        ->label('Tambah Data Faktur');
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
            ->title('Data Faktur Disimpan')
            ->body('Data Faktur telah berhasil disimpan.');
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = filament()->getTenant()->id;
        return $data;
    }
}
