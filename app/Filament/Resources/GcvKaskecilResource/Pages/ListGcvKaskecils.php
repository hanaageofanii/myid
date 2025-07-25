<?php

namespace App\Filament\Resources\GcvKaskecilResource\Pages;

use App\Filament\Resources\GcvKaskecilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvKaskecilResource\Widgets\gcv_kaskecilStats;


class ListGcvKaskecils extends ListRecords
{
    protected static string $resource = GcvKaskecilResource::class;

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
            gcv_kaskecilStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
