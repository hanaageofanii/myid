<?php

namespace App\Filament\Resources\AjbResource\Pages;

use App\Filament\Resources\AjbResource;
use App\Filament\Resources\AjbResource\Widgets\AjbStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAjbs extends ListRecords
{
    protected static string $resource = AjbResource::class;

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
            AjbStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
