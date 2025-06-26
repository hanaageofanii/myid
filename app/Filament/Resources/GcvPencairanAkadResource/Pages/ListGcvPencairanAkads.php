<?php

namespace App\Filament\Resources\GcvPencairanAkadResource\Pages;

use App\Filament\Resources\GcvPencairanAkadResource;
use App\Filament\Resources\GcvPencairanAkadResource\Widgets\gcv_pencairan_akadStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvPencairanAkads extends ListRecords
{
    protected static string $resource = GcvPencairanAkadResource::class;

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
            gcv_pencairan_akadStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
