<?php

namespace App\Filament\Resources\PengajuanDajamResource\Pages;

use App\Filament\Resources\PengajuanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanDajam extends EditRecord
{
    protected static string $resource = PengajuanDajamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
