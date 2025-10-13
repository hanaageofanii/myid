<?php

namespace App\Filament\Resources\KartuKontrolGCVResource\Pages;

use App\Filament\Resources\KartuKontrolGCVResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateKartuKontrolGCV extends CreateRecord
{
    protected static string $resource = KartuKontrolGCVResource::class;
protected static ?string $title = "Kartu Kontrol";
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
            ->title('Kartu Kontrol Disimpan')
            ->body('Kartu Kontrol telah berhasil disimpan.');
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