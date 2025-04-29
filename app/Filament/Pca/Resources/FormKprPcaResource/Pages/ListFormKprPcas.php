<?php

namespace App\Filament\Pca\Resources\FormKprPcaResource\Pages;

use App\Filament\Pca\Resources\FormKprPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         KPRStats::class,
    //     ];
    // }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
