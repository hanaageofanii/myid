<?php

namespace App\Filament\Resources\VerifikasiDajamPcaResource\Pages;

use App\Filament\Resources\VerifikasiDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VerifikasiDajamPcaResource\Widgets\verifikasi_dajam_pca;
use App\Filament\Resources\VerifikasiDajamPcaResource\Widgets\VerifikasiDajamPcaStats;

class ListVerifikasiDajamPcas extends ListRecords
{
    protected static string $resource = VerifikasiDajamPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Verifikasi Dajam'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            VerifikasiDajamPcaStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}

