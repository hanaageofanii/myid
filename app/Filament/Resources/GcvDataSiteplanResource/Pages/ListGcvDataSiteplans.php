<?php

namespace App\Filament\Resources\GcvDataSiteplanResource\Pages;

use App\Filament\Resources\GcvDataSiteplanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvDataSiteplans extends ListRecords
{
    protected static string $resource = GcvDataSiteplanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
