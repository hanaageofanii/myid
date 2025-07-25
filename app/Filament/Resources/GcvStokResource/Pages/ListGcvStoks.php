<?php

namespace App\Filament\Resources\GcvStokResource\Pages;

use App\Filament\Resources\GcvStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvStokResource\Widgets\gcv_stokStats;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;


class ListGcvStoks extends ListRecords
{
    protected static string $resource = GcvStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Bookingan'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_stokStats::class,
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
                ->emptyStateDescription('Silakan buat data Stok')
                ->emptyStateHeading('Belum ada data Stok')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data Stok')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}