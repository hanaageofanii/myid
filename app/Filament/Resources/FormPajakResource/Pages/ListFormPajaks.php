<?php

namespace App\Filament\Resources\FormPajakResource\Pages;

use App\Filament\Resources\FormPajakResource;
use App\Filament\Resources\FormPajakResource\Widgets\PajakStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormPajaks extends ListRecords
{
    protected static string $resource = FormPajakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Validasi PPH'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            PajakStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
