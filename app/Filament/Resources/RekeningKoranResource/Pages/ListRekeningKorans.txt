<?php

namespace App\Filament\Resources\RekeningKoranResource\Pages;

use App\Filament\Resources\RekeningKoranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RekeningKoranResource\Widgets\RekeningkoranStats;

use App\Models\rekening_koran;



class ListRekeningKorans extends ListRecords
{
    protected static string $resource = RekeningKoranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Rekening Koran'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            rekeningkoranStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
