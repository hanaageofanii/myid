<?php

namespace App\Filament\Resources\PpnTkrResource\Pages;

use App\Filament\Resources\PpnTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPpnTkr extends EditRecord
{
    protected static string $resource = PpnTkrResource::class;

    protected static ?string $title = "Ubah Data Faktur";


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Faktur')
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
