<?php

namespace App\Filament\Resources\StokTkrResource\Pages;

use App\Filament\Resources\StokTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStokTkr extends EditRecord
{
    protected static string $resource = StokTkrResource::class;

    protected static ?string $title = "Ubah Data TKR";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data TKR')
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

