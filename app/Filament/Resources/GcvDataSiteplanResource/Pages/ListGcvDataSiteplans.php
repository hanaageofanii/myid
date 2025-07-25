<?php

namespace App\Filament\Resources\GcvDataSiteplanResource\Pages;

use App\Filament\Resources\GcvDataSiteplanResource;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
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

        public function getTable(): Table
        {
            return parent::getTable()
                ->emptyStateIcon('heroicon-o-bookmark')
                ->emptyStateDescription('Silakan buat data siteplan')
                ->emptyStateHeading('Belum ada data siteplan')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data siteplan')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}
