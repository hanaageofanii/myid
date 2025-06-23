<?php

namespace App\Filament\Resources\GcvDataSiteplanResource\Pages;

use App\Filament\Resources\GcvDataSiteplanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvDataSiteplan extends EditRecord
{
    protected static string $resource = GcvDataSiteplanResource::class;

       protected static ?string $title = "Ubah Data Siteplan";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Siteplan')
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

