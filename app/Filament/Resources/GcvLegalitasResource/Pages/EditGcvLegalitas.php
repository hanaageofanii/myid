<?php

namespace App\Filament\Resources\GcvLegalitasResource\Pages;

use App\Filament\Resources\GcvLegalitasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;


class EditGcvLegalitas extends EditRecord
{
    protected static string $resource = GcvLegalitasResource::class;

    protected static ?string $title = "Ubah Data Legalitas";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Legalitas')
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