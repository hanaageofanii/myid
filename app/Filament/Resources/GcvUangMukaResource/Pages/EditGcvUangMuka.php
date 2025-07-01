<?php

namespace App\Filament\Resources\GcvUangMukaResource\Pages;

use App\Filament\Resources\GcvUangMukaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvUangMuka extends EditRecord
{
    protected static string $resource = GcvUangMukaResource::class;

   protected static ?string $title = "Ubah Data Uang  Muka";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Uang Muka')
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