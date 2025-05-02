<?php

namespace App\Filament\Resources\PengajuanDajamTkrResource\Pages;

use App\Filament\Resources\PengajuanDajamTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanDajamTkr extends EditRecord
{
    protected static string $resource = PengajuanDajamTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
