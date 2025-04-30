<?php

namespace App\Filament\Resources\FormPpnPcaResource\Pages;

use App\Filament\Resources\FormPpnPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FormPpnPcaResource\Widgets\form_ppn_pca;


class ListFormPpnPcas extends ListRecords
{
    protected static string $resource = FormPpnPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Faktur Pajak'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            form_ppn_pca::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
