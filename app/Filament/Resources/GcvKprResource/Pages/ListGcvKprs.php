<?php

namespace App\Filament\Resources\GcvKprResource\Pages;

use App\Filament\Resources\GcvKprResource;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvKprResource\Widgets\gcv_kprStats;


class ListGcvKprs extends ListRecords
{
    protected static string $resource = GcvKprResource::class;

   protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Akad KPR'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            gcv_kprStats::class,
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
                ->emptyStateDescription('Silakan buat data Akad KPR')
                ->emptyStateHeading('Belum ada data Akad KPR')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data Akad KPR')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}
