<?php

namespace App\Filament\Resources\PajakTkrResource\Pages;

use App\Filament\Resources\PajakTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PajakTkrResource\Widgets\PajakTkr;


class ListPajakTkrs extends ListRecords
{
    protected static string $resource = PajakTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Validasi PPH'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            PajakTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
