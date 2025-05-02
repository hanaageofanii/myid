<?php

namespace App\Filament\Resources\FormKprTkrResource\Pages;

use App\Filament\Resources\FormKprTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormKprTkr extends EditRecord
{
    protected static string $resource = FormKprTkrResource::class;

    protected static ?string $title = "Ubah Data Penjualan";


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data KPR')
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
