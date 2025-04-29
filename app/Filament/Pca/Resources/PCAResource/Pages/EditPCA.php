<?php

namespace App\Filament\Pca\Resources\PCAResource\Pages;

use App\Filament\Pca\Resources\PCAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPCA extends EditRecord
{
    protected static string $resource = PCAResource::class;

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
