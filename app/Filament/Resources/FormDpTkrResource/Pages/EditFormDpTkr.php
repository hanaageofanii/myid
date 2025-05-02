<?php

namespace App\Filament\Resources\FormDpTkrResource\Pages;

use App\Filament\Resources\FormDpTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormDpTkr extends EditRecord
{
    protected static string $resource = FormDpTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
