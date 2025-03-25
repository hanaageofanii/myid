<?php

namespace App\Filament\Resources\VerifikasiDajamResource\Pages;

use App\Filament\Resources\VerifikasiDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiDajam extends EditRecord
{
    protected static string $resource = VerifikasiDajamResource::class;

    protected static ?string $title = "Ubah Data Verifikasi Dajam";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Verifikasi Dajam')
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
