<?php

namespace App\Filament\Pca\Resources\AuditPCAResource\Pages;

use App\Filament\Pca\Resources\AuditPCAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditPCA extends EditRecord
{
    protected static string $resource = AuditPCAResource::class;

    protected static ?string $title = "Ubah Data Audit PCA";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Audit PCA')
            ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->siteplan}")
            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?"),


        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
