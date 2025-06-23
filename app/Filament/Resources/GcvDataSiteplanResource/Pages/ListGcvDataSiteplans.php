<?php

namespace App\Filament\Resources\GcvDataSiteplanResource\Pages;

use App\Filament\Resources\GcvDataSiteplanResource;
use App\Filament\Resources\GcvDataSiteplanResource\Widgets\gcvDataSiteplanStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvDataSiteplans extends ListRecords
{
    protected static string $resource = GcvDataSiteplanResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Siteplan'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GcvDataSiteplanStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
