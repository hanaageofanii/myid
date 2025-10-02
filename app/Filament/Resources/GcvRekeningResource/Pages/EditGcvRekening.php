<?php

namespace App\Filament\Resources\GcvRekeningResource\Pages;

use App\Filament\Resources\GcvRekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvRekening extends EditRecord
{
    protected static string $resource = GcvRekeningResource::class;

    protected static ?string $title = "Ubah Data Master Rekening";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Master Rekening')
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