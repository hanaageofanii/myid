<?php

namespace App\Filament\Resources\RekonsilResource\Pages;

use App\Filament\Resources\RekonsilResource;
use App\Filament\Resources\RekonsilResource\Widgets\rekonsil;
use App\Filament\Resources\RekonsilResource\Widgets\rekonsilStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRekonsils extends ListRecords
{
    protected static string $resource = RekonsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Transaksi Internal'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            rekonsilStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
