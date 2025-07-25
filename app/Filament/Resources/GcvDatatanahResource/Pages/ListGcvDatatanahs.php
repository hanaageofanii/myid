<?php

namespace App\Filament\Resources\GcvDatatanahResource\Pages;

use App\Filament\Resources\GcvDatatanahResource;
use App\Filament\Resources\GcvDatatanahResource\Widgets\gcv_datatanahStats;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListGcvDatatanahs extends ListRecords
{
    protected static string $resource = GcvDatatanahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Tanah'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
                gcv_datatanahStats::class,
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
                ->emptyStateDescription('Silakan buat data data tanah')
                ->emptyStateHeading('Belum ada data data tanah')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data data tanah')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}