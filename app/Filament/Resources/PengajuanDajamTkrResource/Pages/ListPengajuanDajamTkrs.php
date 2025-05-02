<?php

namespace App\Filament\Resources\PengajuanDajamTkrResource\Pages;

use App\Filament\Resources\PengajuanDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PengajuanDajamTkrResource\Widgets\PengajuanDajamTkr;


class ListPengajuanDajamTkrs extends ListRecords
{
    protected static string $resource = PengajuanDajamTkrResource::class;

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
            PengajuanDajamTkr::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

