<?php

namespace App\Filament\Resources\GcvPengajuanDajamResource\Pages;

use App\Filament\Resources\GcvPengajuanDajamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvPengajuanDajamResource\Widgets\gcv_pengajuan_dajamStats;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;


class ListGcvPengajuanDajams extends ListRecords
{
    protected static string $resource = GcvPengajuanDajamResource::class;

   protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Pengajuan Dajam'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcv_pengajuan_dajamStats::class,
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
                ->emptyStateDescription('Silakan buat data Pengajuan Dajam')
                ->emptyStateHeading('Belum ada data Pengajuan Dajam')
                ->emptyStateActions([
                    Action::make('create')
                        ->label('Buat Data Pengajuan Dajam')
                        ->url($this->getResource()::getUrl('create'))
                        ->icon('heroicon-m-plus')
                        ->button(),
                ]);
        }

}