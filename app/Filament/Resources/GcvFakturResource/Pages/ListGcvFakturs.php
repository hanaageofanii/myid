<?php

namespace App\Filament\Resources\GcvFakturResource\Pages;

use App\Filament\Resources\GcvFakturResource;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGcvFakturs extends ListRecords
{
    protected static string $resource = GcvFakturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

        public function getTable(): Table
        {
            return parent::getTable()
                ->emptyStateIcon('heroicon-o-bookmark')
                ->emptyStateDescription('Silakan buat data faktur   ')
                ->emptyStateHeading('Belum ada data faktur  ')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data faktur   ')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}
