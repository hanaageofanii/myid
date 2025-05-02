<?php

namespace App\Filament\Resources\PencairanAkadTkrResource\Pages;

use App\Filament\Resources\PencairanAkadTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\PencairanAkadTkr;
// use App\Filament\Resources\PencairanAkadTkrResource\Widgets\PencairanAkadTkr;


class ListPencairanAkadTkrs extends ListRecords
{
    protected static string $resource = PencairanAkadTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Pencairan Akad'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            PencairanAkadTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

