<?php

namespace App\Filament\Resources\PpnTkrResource\Pages;

use App\Filament\Resources\PpnTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PpnTkrResource\Widgets\PpnTkr;


class ListPpnTkrs extends ListRecords
{
    protected static string $resource = PpnTkrResource::class;

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
            PpnTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

