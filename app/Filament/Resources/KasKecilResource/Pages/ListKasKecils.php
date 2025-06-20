<?php

namespace App\Filament\Resources\KasKecilResource\Pages;

use App\Filament\Resources\KasKecilResource;
use App\Filament\Resources\KasKecilResource\Widgets\KasKecilStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKasKecils extends ListRecords
{
     protected static string $resource = KasKecilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Kas Kecil'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KasKecilStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
