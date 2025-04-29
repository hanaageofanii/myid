<?php

namespace App\Filament\Resources\FormKprPcaResource\Pages;

use App\Filament\Resources\FormKprPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormKprPca extends EditRecord
{
    protected static string $resource = FormKprPcaResource::class;

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
