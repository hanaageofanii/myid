<?php

namespace App\Filament\Pca\Resources\AjbPCAResource\Pages;

use App\Filament\Pca\Resources\AjbPCAResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAjbPCA extends EditRecord
{
    protected static string $resource = AjbPCAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
