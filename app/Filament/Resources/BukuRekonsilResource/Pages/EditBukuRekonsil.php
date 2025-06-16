<?php

namespace App\Filament\Resources\BukuRekonsilResource\Pages;

use App\Filament\Resources\BukuRekonsilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBukuRekonsil extends EditRecord
{
    protected static string $resource = BukuRekonsilResource::class;

    protected static ?string $title = "Ubah Data Rekonsil";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Rekonsil')
            ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->no_check}")
            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus {$record->no_check}?"),

        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
    
}
