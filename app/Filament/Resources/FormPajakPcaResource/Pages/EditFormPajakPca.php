<?php

namespace App\Filament\Resources\FormPajakPcaResource\Pages;

use App\Filament\Resources\FormPajakPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPajakPca extends EditRecord
{
    protected static string $resource = FormPajakPcaResource::class;

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
