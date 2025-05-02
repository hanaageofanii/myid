<?php

namespace App\Filament\Resources\AuditTkrResource\Pages;

use App\Filament\Resources\AuditTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuditTkr extends EditRecord
{
    protected static string $resource = AuditTkrResource::class;

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
