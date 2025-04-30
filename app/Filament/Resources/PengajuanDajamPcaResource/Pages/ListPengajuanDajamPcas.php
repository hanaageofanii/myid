<?php

namespace App\Filament\Resources\PengajuanDajamPcaResource\Pages;

use App\Filament\Resources\PengajuanDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanDajamPcas extends ListRecords
{
    protected static string $resource = PengajuanDajamPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
