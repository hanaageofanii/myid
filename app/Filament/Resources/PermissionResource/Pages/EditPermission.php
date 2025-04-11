<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected static ?string $title = "Ubah Pemission";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Permission')
            ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->name}")
            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->name}?"),

        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Permission Updated';
    }
}