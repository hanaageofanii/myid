<?php

namespace App\Filament\Resources\BukuRekonsilResource\Pages;

use App\Filament\Resources\BukuRekonsilResource;
use Filament\Actions;
use App\Filament\Resources\BukuRekonsilResource\Widgets\buku_rekonsilStats;
use Filament\Resources\Pages\ListRecords;

class ListBukuRekonsils extends ListRecords
{
 protected static string $resource = BukuRekonsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Rekonsil'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            buku_rekonsilStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}