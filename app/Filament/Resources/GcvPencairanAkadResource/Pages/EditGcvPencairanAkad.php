<?php

namespace App\Filament\Resources\GcvPencairanAkadResource\Pages;

use App\Filament\Resources\GcvPencairanAkadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvPencairanAkad extends EditRecord
{
    protected static string $resource = GcvPencairanAkadResource::class;

    protected static ?string $title = "Ubah Data Pencairan Akad";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Pencairan Akad')
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
