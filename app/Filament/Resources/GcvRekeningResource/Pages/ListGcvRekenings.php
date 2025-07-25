<?php

namespace App\Filament\Resources\GcvRekeningResource\Pages;

use App\Filament\Resources\GcvRekeningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class ListGcvRekenings extends ListRecords
{
    protected static string $resource = GcvRekeningResource::class;

  protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Rekening'),
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
                ->emptyStateDescription('Silakan buat data Rekening')
                ->emptyStateHeading('Belum ada data Rekening')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data Rekening')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }
}
