<?php

namespace App\Filament\Resources\GcvStokResource\Pages;

use App\Filament\Resources\GcvStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvStokResource\Widgets\gcv_stokStats;


class ListGcvStoks extends ListRecords
{
    protected static string $resource = GcvStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Bookingan'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_stokStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
