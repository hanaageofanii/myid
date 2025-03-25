<?php

namespace App\Filament\Resources\PengajuanDajamResource\Pages;

use App\Filament\Resources\PengajuanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PengajuanDajamResource\Widgets\PenDajamStats;


class ListPengajuanDajams extends ListRecords
{
    protected static string $resource = PengajuanDajamResource::class;

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
            PenDajamStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

