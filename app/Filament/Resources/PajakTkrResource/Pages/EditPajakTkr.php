<?php

namespace App\Filament\Resources\PajakTkrResource\Pages;

use App\Filament\Resources\PajakTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPajakTkr extends EditRecord
{
    protected static string $resource = PajakTkrResource::class;

    protected static ?string $title = "Ubah Data Validasi PPH";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Validasi')
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
