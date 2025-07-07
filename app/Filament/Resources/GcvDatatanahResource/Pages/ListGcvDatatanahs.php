<?php

namespace App\Filament\Resources\GcvDatatanahResource\Pages;

use App\Filament\Resources\GcvDatatanahResource;
use App\Filament\Resources\GcvDatatanahResource\Widgets\gcv_datatanahStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvDatatanahs extends ListRecords
{
protected static string $resource = GcvDatatanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Tanah'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
                gcv_datatanahStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
