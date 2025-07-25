<?php

namespace App\Filament\Resources\BukuRekonsilResource\Pages;

use App\Filament\Resources\BukuRekonsilResource;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Filament\Resources\BukuRekonsilResource\Widgets\buku_rekonsilStats;
use Filament\Resources\Pages\ListRecords;

class ListBukuRekonsils extends ListRecords
{
 protected static string $resource = BukuRekonsilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Rekonsil'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            buku_rekonsilStats::class,
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
                ->emptyStateDescription('Silakan buat data buku rekonsil')
                ->emptyStateHeading('Belum ada data buku rekonsil')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data buku rekonsil')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}