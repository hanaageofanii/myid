<?php

namespace App\Filament\Resources\AjbTkrResource\Pages;

use App\Filament\Resources\AjbTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAjbTkr extends EditRecord
{
    protected static string $resource = AjbTkrResource::class;

    protected static ?string $title = "Ubah Data AJB";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data AJB')
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
