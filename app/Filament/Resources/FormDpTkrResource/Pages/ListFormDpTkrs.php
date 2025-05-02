<?php

namespace App\Filament\Resources\FormDpTkrResource\Pages;

use App\Filament\Resources\FormDpTkrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormDpTkrs extends ListRecords
{
    protected static string $resource = FormDpTkrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
