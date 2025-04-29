<?php

namespace App\Filament\Resources\PcaResource\Pages;

use App\Filament\Resources\PcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PcaResource\Widgets\pcaStats;


class ListPcas extends ListRecords
{
    protected static string $resource = PcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data PCA'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            pcaStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
