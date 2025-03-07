<?php

namespace App\Filament\Resources\FormKprResource\Pages;

use App\Filament\Resources\FormKprResource;
use App\Filament\Resources\FormKprResource\Widgets\KPRStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormKprs extends ListRecords
{
    protected static string $resource = FormKprResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data KPR'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KPRStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
