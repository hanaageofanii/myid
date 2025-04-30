<?php

namespace App\Filament\Resources\PengajuanDajamPcaResource\Pages;

use App\Filament\Resources\PengajuanDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanDajamPca extends EditRecord
{
    protected static string $resource = PengajuanDajamPcaResource::class;

    protected static ?string $title = "Ubah Data Pengajuan Dajam";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Pengajuan Dajam')
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

