<?php

namespace App\Filament\Resources\GcvPengajuanDajamResource\Pages;

use App\Filament\Resources\GcvPengajuanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvPengajuanDajamResource\Widgets\gcv_pengajuan_dajamStats;


class ListGcvPengajuanDajams extends ListRecords
{
    protected static string $resource = GcvPengajuanDajamResource::class;

   protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Pengajuan Dajam'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcv_pengajuan_dajamStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
