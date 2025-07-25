<?php

namespace App\Filament\Resources\GcvDatatandaterimaResource\Pages;

use App\Filament\Resources\GcvDatatandaterimaResource;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Filament\Resources\GcvDatatandaterimaResource\Widgets\gcv_datatandaterimaStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvDatatandaterimas extends ListRecords
{
    protected static string $resource = GcvDatatandaterimaResource::class;

      protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Tanda Terima'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_datatandaterimaStats::class,
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
                ->emptyStateDescription('Silakan buat data tanda terima')
                ->emptyStateHeading('Belum ada data tanda terima')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data tanda terima')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}
