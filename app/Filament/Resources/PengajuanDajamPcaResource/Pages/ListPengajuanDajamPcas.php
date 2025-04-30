<?php

namespace App\Filament\Resources\PengajuanDajamPcaResource\Pages;

use App\Filament\Resources\PengajuanDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PengajuanDajamPcaResource\Widgets\pengajuan_dajam_pca;

class ListPengajuanDajamPcas extends ListRecords
{
    protected static string $resource = PengajuanDajamPcaResource::class;

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
            pengajuan_dajam_pca::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

