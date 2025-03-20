<?php

namespace App\Filament\Resources\PencairanAkadResource\Pages;

use App\Filament\Resources\PencairanAkadResource;
use App\Filament\Resources\PencairanAkadResource\Widgets\AkadStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPencairanAkads extends ListRecords
{
    protected static string $resource = PencairanAkadResource::class;

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
            AkadStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
