<?php

namespace App\Filament\Resources\GCVResource\Pages;

use App\Filament\Resources\GCVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGCV extends EditRecord
{
    protected static string $resource = GCVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data GCV'),
        ];
    }
}
