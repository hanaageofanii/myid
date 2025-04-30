<?php

namespace App\Filament\Resources\PengajuanDajamPcaResource\Pages;

use App\Filament\Resources\PengajuanDajamPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanDajamPca extends EditRecord
{
    protected static string $resource = PengajuanDajamPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
