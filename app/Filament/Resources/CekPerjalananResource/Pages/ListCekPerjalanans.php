<?php

namespace App\Filament\Resources\CekPerjalananResource\Pages;

use App\Filament\Resources\CekPerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCekPerjalanans extends ListRecords
{
    protected static string $resource = CekPerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
