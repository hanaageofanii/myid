<?php

namespace App\Filament\Pca\Resources\AjbPCAResource\Pages;

use App\Filament\Pca\Resources\AjbPCAResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAjbPCAS extends ListRecords
{
    protected static string $resource = AjbPCAResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data AJB'),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         AjbStats::class,
    //     ];
    // }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
