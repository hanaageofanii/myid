<?php

namespace App\Filament\Resources\GcvMasterDajamResource\Pages;

use App\Filament\Resources\GcvMasterDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvMasterDajam extends EditRecord
{
    protected static string $resource = GcvMasterDajamResource::class;

    protected static ?string $title = "Ubah Data Ajb";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Ajb')
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