<?php

namespace App\Filament\Resources\GcvDataSiteplanResource\Pages;

use App\Filament\Resources\GcvDataSiteplanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateGcvDataSiteplan extends CreateRecord
{
    protected static string $resource = GcvDataSiteplanResource::class;
    protected static ?string $title = "Buat Data Siteplan";
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
        ->label('Tambah Data Siteplan');
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
            ->title('Data Siteplan Disimpan')
            ->body('Data Siteplan telah berhasil disimpan.');
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