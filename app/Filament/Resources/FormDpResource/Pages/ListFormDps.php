<?php

namespace App\Filament\Resources\FormDpResource\Pages;

use App\Filament\Resources\FormDpResource;
use App\Filament\Resources\FormDpResource\Widgets\DPStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormDps extends ListRecords
{
    protected static string $resource = FormDpResource::class;

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
            DPStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
