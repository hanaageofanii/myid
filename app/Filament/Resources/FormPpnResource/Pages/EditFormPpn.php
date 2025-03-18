<?php

namespace App\Filament\Resources\FormPpnResource\Pages;

use App\Filament\Resources\FormPpnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPpn extends EditRecord
{
    protected static string $resource = FormPpnResource::class;

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
