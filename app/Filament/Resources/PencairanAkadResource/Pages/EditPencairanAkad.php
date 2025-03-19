<?php

namespace App\Filament\Resources\PencairanAkadResource\Pages;

use App\Filament\Resources\PencairanAkadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPencairanAkad extends EditRecord
{
    protected static string $resource = PencairanAkadResource::class;

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
