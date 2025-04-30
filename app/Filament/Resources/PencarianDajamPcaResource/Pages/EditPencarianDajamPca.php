<?php

namespace App\Filament\Resources\PencarianDajamPcaResource\Pages;

use App\Filament\Resources\PencarianDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPencarianDajamPca extends EditRecord
{
    protected static string $resource = PencarianDajamPcaResource::class;

    protected static ?string $title = "Ubah Data Pencairan Dajam";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Pencairan Dajam')
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
