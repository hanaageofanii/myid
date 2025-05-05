<?php

namespace App\Filament\Resources\VerifikasiDajamTkrResource\Pages;

use App\Filament\Resources\VerifikasiDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VerifikasiDajamTkrResource\Widgets\VerifikasiDajamTkrStats;



class ListVerifikasiDajamTkrs extends ListRecords
{
    protected static string $resource = VerifikasiDajamTkrResource::class;

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
            VerifikasiDajamTkrStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}


