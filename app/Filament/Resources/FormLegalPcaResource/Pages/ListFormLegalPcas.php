<?php

namespace App\Filament\Resources\FormLegalPcaResource\Pages;

use App\Filament\Resources\FormLegalPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FormLegalPcaResource\Widgets\form_legal_pca;


class ListFormLegalPcas extends ListRecords
{
    protected static string $resource = FormLegalPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Sertifikat'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            form_legal_pca::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
