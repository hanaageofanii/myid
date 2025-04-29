<?php

namespace App\Filament\Pca\Resources\FormKprPcaResource\Pages;

use App\Filament\Pca\Resources\FormKprPcaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormKprPca extends EditRecord
{
    protected static string $resource = FormKprPcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
