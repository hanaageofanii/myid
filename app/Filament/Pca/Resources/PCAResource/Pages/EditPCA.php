<?php

namespace App\Filament\Pca\Resources\PCAResource\Pages;

use App\Filament\Pca\Resources\PCAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPCA extends EditRecord
{
    protected static string $resource = PCAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
