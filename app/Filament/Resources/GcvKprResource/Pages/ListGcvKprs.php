<?php

namespace App\Filament\Resources\GcvKprResource\Pages;

use App\Filament\Resources\GcvKprResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvKprResource\Widgets\gcv_kprStats;


class ListGcvKprs extends ListRecords
{
    protected static string $resource = GcvKprResource::class;

   protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data KPR'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_kprStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
