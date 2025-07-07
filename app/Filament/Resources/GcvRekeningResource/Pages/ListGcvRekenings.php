<?php

namespace App\Filament\Resources\GcvRekeningResource\Pages;

use App\Filament\Resources\GcvRekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvRekenings extends ListRecords
{
    protected static string $resource = GcvRekeningResource::class;

  protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Rekening'),
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
