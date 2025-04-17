<?php

namespace App\Filament\Resources\CekPerjalananResource\Pages;

use App\Filament\Resources\CekPerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCekPerjalanan extends EditRecord
{
    protected static string $resource = CekPerjalananResource::class;

    protected static ?string $title = "Ubah Data Cek Rekening & Transaksi";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Cek Rekening & Transaksi')
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
