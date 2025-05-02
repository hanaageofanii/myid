<?php

namespace App\Filament\Resources\FormDpTkrResource\Pages;

use App\Filament\Resources\FormDpTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FormDpTkrResource\Widgets\FormDpTkr;


class ListFormDpTkrs extends ListRecords
{
    protected static string $resource = FormDpTkrResource::class;

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
            FormDpTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
