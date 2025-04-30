<?php

namespace App\Filament\Resources\PencairanAkadPcaResource\Pages;

use App\Filament\Resources\PencairanAkadPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PencairanAkadPcaResource\Widgets\pencairan_akad_pca;


class ListPencairanAkadPcas extends ListRecords
{
    protected static string $resource = PencairanAkadPcaResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Pencairan Akad'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            pencairan_akad_pca::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
