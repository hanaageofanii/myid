<?php

namespace App\Filament\Resources\GcvDatatandaterimaResource\Pages;

use App\Filament\Resources\GcvDatatandaterimaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvDatatandaterima extends EditRecord
{
    protected static string $resource = GcvDatatandaterimaResource::class;

    protected static ?string $title = "Ubah Data Tanda Terima";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Tanda Terima')
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

