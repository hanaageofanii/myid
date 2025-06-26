<?php

namespace App\Filament\Resources\GcvKprResource\Pages;

use App\Filament\Resources\GcvKprResource;
use Filament\Actions;

use Filament\Resources\Pages\EditRecord;

class EditGcvKpr extends EditRecord
{
     protected static string $resource = GcvKprResource::class;

   protected static ?string $title = "Ubah Data Akad";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Akad KPR')
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