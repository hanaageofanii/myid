<?php

namespace App\Filament\Resources\PencairanAkadPcaResource\Pages;

use App\Filament\Resources\PencairanAkadPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPencairanAkadPca extends EditRecord
{
    protected static string $resource = PencairanAkadPcaResource::class;

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
