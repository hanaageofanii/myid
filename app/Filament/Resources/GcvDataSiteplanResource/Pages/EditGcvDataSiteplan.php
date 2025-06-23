<?php

namespace App\Filament\Resources\GcvDataSiteplanResource\Pages;

use App\Filament\Resources\GcvDataSiteplanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGcvDataSiteplan extends EditRecord
{
    protected static string $resource = GcvDataSiteplanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
