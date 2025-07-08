<?php

namespace App\Filament\Resources\GcvPengajuanBnResource\Pages;

use App\Filament\Resources\GcvPengajuanBnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvPengajuanBns extends ListRecords
{
    protected static string $resource = GcvPengajuanBnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
