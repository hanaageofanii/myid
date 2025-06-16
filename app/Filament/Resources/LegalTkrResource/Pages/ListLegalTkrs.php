<?php

namespace App\Filament\Resources\LegalTkrResource\Pages;

use App\Filament\Resources\LegalTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LegalTkrResource\Widgets\LegalTkr;


class ListLegalTkrs extends ListRecords
{
    protected static string $resource = LegalTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Legalitas'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            LegalTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
