<?php

namespace App\Filament\Resources\PencairanDajamTkrResource\Pages;

use App\Filament\Resources\PencairanDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPencairanDajamTkr extends EditRecord
{
    protected static string $resource = PencairanDajamTkrResource::class;

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
