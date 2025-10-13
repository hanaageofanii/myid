<?php

namespace App\Filament\Resources\KartuKontrolGCVResource\Pages;

use App\Filament\Resources\KartuKontrolGCVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKartuKontrolGCV extends EditRecord
{
    protected static string $resource = KartuKontrolGCVResource::class;

    protected static ?string $title = "Ubah Data Kartu Kontrol";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Kartu Kontrol')
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
