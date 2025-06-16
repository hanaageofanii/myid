<?php

namespace App\Filament\Resources\FormLegalResource\Pages;

use App\Filament\Resources\FormLegalResource;
use App\Filament\Resources\FormLegalResource\Widgets\SertifikatStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormLegals extends ListRecords
{
    protected static string $resource = FormLegalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Legalitas'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            SertifikatStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
