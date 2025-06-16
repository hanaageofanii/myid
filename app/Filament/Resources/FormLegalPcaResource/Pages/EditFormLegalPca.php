<?php

namespace App\Filament\Resources\FormLegalPcaResource\Pages;

use App\Filament\Resources\FormLegalPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormLegalPca extends EditRecord
{
    protected static string $resource = FormLegalPcaResource::class;

    protected static ?string $title = "Ubah Data Legalitas";

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
