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
            Actions\CreateAction::make()
            ->label('Buat Data PCA'),
        ];
    }
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         PCAOverviewWei::class,
    //     ];
    // }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
