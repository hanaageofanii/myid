<?php

namespace App\Filament\Resources\RekonsilResource\Pages;

use App\Filament\Resources\RekonsilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekonsil extends EditRecord
{
    protected static string $resource = RekonsilResource::class;

    protected static ?string $title = "Ubah Data Transaksi Internal";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Transaksi Internal Dajam')
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
