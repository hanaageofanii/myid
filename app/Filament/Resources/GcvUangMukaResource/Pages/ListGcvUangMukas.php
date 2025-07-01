<?php

namespace App\Filament\Resources\GcvUangMukaResource\Pages;

use App\Filament\Resources\GcvUangMukaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvUangMukaResource\Widgets\gcv_uangMukaStats;


class ListGcvUangMukas extends ListRecords
{
    protected static string $resource = GcvUangMukaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Uang Muka'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcv_uangMukaStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
