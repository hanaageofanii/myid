<?php

namespace App\Filament\Resources\KartuKontrolGCVResource\Pages;

use App\Filament\Resources\KartuKontrolGCVResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\KartuKontrolGCVResource\Widgets\kartu_kontrolStats;


class ListKartuKontrolGCVS extends ListRecords
{
    protected static string $resource = KartuKontrolGCVResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Kartu Kontrol'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            kartu_kontrolStats::class,
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
                ->emptyStateDescription('Silakan buat data Kartu Kontrol')
                ->emptyStateHeading('Belum ada data Kartu Kontrol')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data Kartu Kontrol')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}