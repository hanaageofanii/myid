<?php

namespace App\Filament\Resources\FormPajakResource\Pages;

use App\Filament\Resources\FormPajakResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPajak extends EditRecord
{
    protected static string $resource = FormPajakResource::class;

    protected static ?string $title = "Ubah Data Validasi PPH";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Validasi')
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
