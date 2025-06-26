<?php

namespace App\Filament\Resources\GcvPencairanDajamResource\Pages;

use App\Filament\Resources\GcvPencairanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvPencairanDajam extends EditRecord
{
    protected static string $resource = GcvPencairanDajamResource::class;

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
