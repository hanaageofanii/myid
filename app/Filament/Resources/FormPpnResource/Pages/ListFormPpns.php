<?php

namespace App\Filament\Resources\FormPpnResource\Pages;

use App\Filament\Resources\FormPpnResource;
use App\Filament\Resources\FormPpnResource\Widgets\PPNStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormPpns extends ListRecords
{
    protected static string $resource = FormPpnResource::class;


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
            PPNStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
