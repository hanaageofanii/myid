<?php

namespace App\Filament\Resources\GCVResource\Pages;

use App\Filament\Resources\GCVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGCV extends EditRecord
{
    protected static string $resource = GCVResource::class;

    protected static ?string $title = "Ubah Data GCV";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data GCV')
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
