<?php

namespace App\Filament\Resources\DajamResource\Pages;

use App\Filament\Resources\DajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDajam extends EditRecord
{
    protected static string $resource = DajamResource::class;

    protected static ?string $title = "Ubah Data Dajam";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Dajam')
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
