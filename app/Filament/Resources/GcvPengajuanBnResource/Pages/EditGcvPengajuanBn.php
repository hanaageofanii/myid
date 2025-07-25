<?php

namespace App\Filament\Resources\GcvPengajuanBnResource\Pages;

use App\Filament\Resources\GcvPengajuanBnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvPengajuanBn extends EditRecord
{
    protected static string $resource = GcvPengajuanBnResource::class;

     protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Pengjuan Bn')
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
