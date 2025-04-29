<?php

namespace App\Filament\Resources\PcaResource\Pages;

use App\Filament\Resources\PcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPca extends EditRecord
{
    protected static string $resource = PcaResource::class;

    protected static ?string $title = "Ubah Data PCA";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data PCA')
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
