<?php

namespace App\Filament\Resources\AjbTkrResource\Pages;

use App\Filament\Resources\AjbTkrResource;
use Filament\Actions;
use App\Filament\Resources\AjbTkrResource\Widgets\AjbTkr;
use Filament\Resources\Pages\ListRecords;

class ListAjbTkrs extends ListRecords
{
    protected static string $resource = AjbTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data AJB'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AjbTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
