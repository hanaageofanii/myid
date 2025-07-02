<?php

namespace App\Filament\Resources\GcvPengajuanDajamResource\Pages;

use App\Filament\Resources\GcvPengajuanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvPengajuanDajams extends ListRecords
{
    protected static string $resource = GcvPengajuanDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
