<?php

namespace App\Filament\Resources\GcvValidasiPphResource\Pages;

use App\Filament\Resources\GcvValidasiPphResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvValidasiPphResource\Widgets\gcv_validasi_pphStats;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;


class ListGcvValidasiPphs extends ListRecords
{
    protected static string $resource = GcvValidasiPphResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Validasi PPH'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcv_validasi_pphStats::class,
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
                ->emptyStateDescription('Silakan buat data PPH')
                ->emptyStateHeading('Belum ada data PPH')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data PPH')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}
