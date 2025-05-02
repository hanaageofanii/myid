<?php

namespace App\Filament\Resources\LegalTkrResource\Pages;

use App\Filament\Resources\LegalTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegalTkr extends EditRecord
{
    protected static string $resource = LegalTkrResource::class;

    protected static ?string $title = "Ubah Data Sertifikat";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Sertifikat')
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
