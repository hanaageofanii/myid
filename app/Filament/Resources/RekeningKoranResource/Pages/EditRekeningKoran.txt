<?php

namespace App\Filament\Resources\RekeningKoranResource\Pages;

use App\Filament\Resources\RekeningKoranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekeningKoran extends EditRecord
{
    protected static string $resource = RekeningKoranResource::class;

    protected static ?string $title = "Ubah Data Rekening Koran";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Rekening Koran')
            ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->no_transakasi}")
            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->no_transaksi}?"),

        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
    
}
