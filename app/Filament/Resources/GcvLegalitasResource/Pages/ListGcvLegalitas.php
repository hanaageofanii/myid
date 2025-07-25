<?php

namespace App\Filament\Resources\GcvLegalitasResource\Pages;

use App\Filament\Resources\GcvLegalitasResource;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvLegalitasResource\Widgets\gcv_legalitasStats;

class ListGcvLegalitas extends ListRecords
{
    protected static string $resource = GcvLegalitasResource::class;

     protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Legalitas'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_legalitasStats::class,
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
                ->emptyStateDescription('Silakan buat data legalitas')
                ->emptyStateHeading('Belum ada data legalitas')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data legalitas')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}
