<?php

namespace App\Filament\Resources\GcvDatatandaterimaResource\Pages;

use App\Filament\Resources\GcvDatatandaterimaResource;
use App\Filament\Resources\GcvDatatandaterimaResource\Widgets\gcv_datatandaterimaStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvDatatandaterimas extends ListRecords
{
    protected static string $resource = GcvDatatandaterimaResource::class;

      protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Tanda Terima'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_datatandaterimaStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}