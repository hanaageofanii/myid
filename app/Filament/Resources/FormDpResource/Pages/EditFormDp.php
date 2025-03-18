<?php

namespace App\Filament\Resources\FormDpResource\Pages;

use App\Filament\Resources\FormDpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormDp extends EditRecord
{
    protected static string $resource = FormDpResource::class;

    protected static ?string $title = "Ubah Data Form DP";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label(fn ($record) => "Hapus {$record->siteplan}") 
            ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->siteplan}")
            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus siteplan {$record->siteplan}?"),

        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
