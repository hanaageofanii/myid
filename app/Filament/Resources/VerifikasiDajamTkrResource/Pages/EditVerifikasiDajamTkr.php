<?php

namespace App\Filament\Resources\VerifikasiDajamTkrResource\Pages;

use App\Filament\Resources\VerifikasiDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiDajamTkr extends EditRecord
{
    protected static string $resource = VerifikasiDajamTkrResource::class;

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
