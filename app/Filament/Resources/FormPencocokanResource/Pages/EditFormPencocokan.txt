<?php

namespace App\Filament\Resources\FormPencocokanResource\Pages;

use App\Filament\Resources\FormPencocokanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormPencocokan extends EditRecord
{
    protected static string $resource = FormPencocokanResource::class;

    protected static ?string $title = "Ubah Data Pencocokan Data";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Pencocokan Data')
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
