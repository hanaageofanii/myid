<?php

namespace App\Filament\Resources\GcvKaskecilResource\Pages;

use App\Filament\Resources\GcvKaskecilResource;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvKaskecilResource\Widgets\gcv_kaskecilStats;


class ListGcvKaskecils extends ListRecords
{
    protected static string $resource = GcvKaskecilResource::class;

   protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Kas Kecil'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_kaskecilStats::class,
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
                ->emptyStateDescription('Silakan buat data kas kecil')
                ->emptyStateHeading('Belum ada data kas kecil')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data kas kecil')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}
