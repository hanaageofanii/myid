<?php

namespace App\Filament\Resources\PencarianDajamPcaResource\Pages;

use App\Filament\Resources\PencarianDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PencairanDajamPcaResource\Widgets\pencairan_dajam_pca;

class ListPencarianDajamPcas extends ListRecords
{
    protected static string $resource = PencarianDajamPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Pencairan Dajam'),
        ];
    }
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         pencairan_dajam_pca::class,
    //     ];
    // }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
