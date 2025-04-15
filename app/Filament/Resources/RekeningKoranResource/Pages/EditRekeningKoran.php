<?php

namespace App\Filament\Resources\RekeningKoranResource\Pages;

use App\Filament\Resources\RekeningKoranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekeningKoran extends EditRecord
{
    protected static string $resource = RekeningKoranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
