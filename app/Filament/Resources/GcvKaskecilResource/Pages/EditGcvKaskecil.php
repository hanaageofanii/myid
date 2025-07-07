<?php

namespace App\Filament\Resources\GcvKaskecilResource\Pages;

use App\Filament\Resources\GcvKaskecilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvKaskecil extends EditRecord
{
    protected static string $resource = GcvKaskecilResource::class;

   protected static ?string $title = "Ubah Kas Kecil";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Kas Kecil')
            ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->deskripsi}")
            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus {$record->deskripsi}?"),

        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }

}
