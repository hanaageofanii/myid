<?php

namespace App\Filament\Resources\FormPencocokanResource\Pages;

use App\Filament\Resources\FormPencocokanResource;
use App\Filament\Resources\FormPencocokanResource\Widgets\form_pencocokan;
use App\Filament\Resources\FormPencocokanResource\Widgets\FormPencocokanStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormPencocokans extends ListRecords
{
    protected static string $resource = FormPencocokanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Pencocokan'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            FormPencocokanStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
