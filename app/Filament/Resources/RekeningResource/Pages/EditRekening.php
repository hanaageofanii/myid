<?php

namespace App\Filament\Resources\RekeningResource\Pages;

use App\Filament\Resources\RekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekening extends EditRecord
{
    protected static string $resource = RekeningResource::class;

    protected static ?string $title = "Ubah Data Rekening";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Rekening')
            ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->rekening}")
            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->rekening}?"),

        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
    
}
