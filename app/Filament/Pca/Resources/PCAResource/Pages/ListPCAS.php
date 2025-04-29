<?php

namespace App\Filament\Pca\Resources\PCAResource\Pages;

use App\Filament\Pca\Resources\PCAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPCAS extends ListRecords
{
    protected static string $resource = PCAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
