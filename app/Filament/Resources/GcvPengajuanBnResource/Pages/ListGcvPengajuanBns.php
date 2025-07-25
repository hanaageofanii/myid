<?php

namespace App\Filament\Resources\GcvPengajuanBnResource\Pages;

use App\Filament\Resources\GcvPengajuanBnResource;
use App\Filament\Resources\GcvPengajuanBnResource\Widgets\gcvPengajuanBnStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvPengajuanBns extends ListRecords
{
    protected static string $resource = GcvPengajuanBnResource::class;

     protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Pencairan Dajam'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcvPengajuanBnStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
