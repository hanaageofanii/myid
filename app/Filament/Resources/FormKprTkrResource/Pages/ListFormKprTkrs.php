<?php

namespace App\Filament\Resources\FormKprTkrResource\Pages;

use App\Filament\Resources\FormKprTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\FormKprTkrResource\Widgets\FormKprTkr;


class ListFormKprTkrs extends ListRecords
{
    protected static string $resource = FormKprTkrResource::class;

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
            FormKprTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
