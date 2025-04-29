<?php

namespace App\Filament\Resources\PcaResource\Pages;

use App\Filament\Resources\PcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPca extends EditRecord
{
    protected static string $resource = PcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
