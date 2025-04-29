<?php

namespace App\Filament\Resources\FormKprPcaResource\Pages;

use App\Filament\Resources\FormKprPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FormKprPcaResource\Widgets\form_kpr_pca;


class ListFormKprPcas extends ListRecords
{
    protected static string $resource = FormKprPcaResource::class;

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
            form_kpr_pca::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
