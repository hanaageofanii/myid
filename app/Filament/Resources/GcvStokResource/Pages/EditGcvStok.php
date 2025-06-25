<?php

namespace App\Filament\Resources\GcvStokResource\Pages;

use App\Filament\Resources\GcvStokResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvStok extends EditRecord
{
    protected static string $resource = GcvStokResource::class;

    protected static ?string $title = "Ubah Data Stok";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Stok')
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