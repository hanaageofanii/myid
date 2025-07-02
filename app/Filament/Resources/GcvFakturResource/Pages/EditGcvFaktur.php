<?php

namespace App\Filament\Resources\GcvFakturResource\Pages;

use App\Filament\Resources\GcvFakturResource;
use Filament\Actions;
use App\Filament\Resources\GcvFakturResource\Widgets\GcvFakturStats;
use Filament\Resources\Pages\EditRecord;

class EditGcvFaktur extends EditRecord
{
    protected static string $resource = GcvFakturResource::class;

protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Faktur Pajak'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GcvFakturStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
