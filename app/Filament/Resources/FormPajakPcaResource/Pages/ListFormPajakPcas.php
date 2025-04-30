<?php

namespace App\Filament\Resources\FormPajakPcaResource\Pages;

use App\Filament\Resources\FormPajakPcaResource;
use Filament\Actions;
use App\Filament\Resources\FormPajakPcaResource\Widgets\form_pajak_pca;

use Filament\Resources\Pages\ListRecords;

class ListFormPajakPcas extends ListRecords
{
    protected static string $resource = FormPajakPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Validasi PPH'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            form_pajak_pca::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
