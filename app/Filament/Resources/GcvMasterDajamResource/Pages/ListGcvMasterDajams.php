<?php

namespace App\Filament\Resources\GcvMasterDajamResource\Pages;

use App\Filament\Resources\GcvMasterDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvAjbResource\Widgets\gcv_AjbStats;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class ListGcvMasterDajams extends ListRecords
{
    protected static string $resource = GcvMasterDajamResource::class;

     protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Ajb'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // gcv_AjbStats::class,
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
                ->emptyStateDescription('Silakan buat data Ajb')
                ->emptyStateHeading('Belum ada data Ajb')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data Ajb')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}