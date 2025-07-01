<?php

namespace App\Filament\Resources\GcvUangMukaResource\Pages;

use App\Filament\Resources\GcvUangMukaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvUangMuka extends EditRecord
{
    protected static string $resource = GcvUangMukaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
