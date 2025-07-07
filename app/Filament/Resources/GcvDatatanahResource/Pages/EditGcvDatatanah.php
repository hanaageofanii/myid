<?php

namespace App\Filament\Resources\GcvDatatanahResource\Pages;

use App\Filament\Resources\GcvDatatanahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvDatatanah extends EditRecord
{
protected static string $resource = GcvDatatanahResource::class;

   protected static ?string $title = "Ubah Data Tanah";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Tanah')
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
