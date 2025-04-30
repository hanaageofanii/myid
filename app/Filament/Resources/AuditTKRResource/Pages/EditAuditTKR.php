<?php

namespace App\Filament\Resources\AuditTKRResource\Pages;

use App\Filament\Resources\AuditTKRResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditTKR extends EditRecord
{
    protected static string $resource = AuditTKRResource::class;

    protected static ?string $title = "Ubah Data Audit TKR";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Audit TKR')
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
