<?php

namespace App\Filament\Resources\KartuKontrolGCVResource\Pages;

use App\Filament\Resources\KartuKontrolGCVResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKartuKontrolGCV extends EditRecord
{
    protected static string $resource = KartuKontrolGCVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
