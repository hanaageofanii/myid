<?php

namespace App\Filament\Resources\FormDpPcaResource\Pages;

use App\Filament\Resources\FormDpPcaResource;
use Filament\Actions;
use App\Filament\Resources\FormDpPcaResource\Widgets\form_dp_pca;
use Filament\Resources\Pages\ListRecords;

class ListFormDpPcas extends ListRecords
{
    protected static string $resource = FormDpPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data DP'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            form_dp_pca::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
