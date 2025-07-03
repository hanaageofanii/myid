<?php

namespace App\Filament\Resources\GcvVerifikasiDajamResource\Pages;

use App\Filament\Resources\GcvVerifikasiDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvVerifikasiDajam extends EditRecord
{
    protected static string $resource = GcvVerifikasiDajamResource::class;

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