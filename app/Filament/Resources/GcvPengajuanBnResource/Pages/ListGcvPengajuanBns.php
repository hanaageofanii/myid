<?php

namespace App\Filament\Resources\GcvPengajuanBnResource\Pages;

use App\Filament\Resources\GcvPengajuanBnResource;
use App\Filament\Resources\GcvPengajuanBnResource\Widgets\gcvPengajuanBnStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;


class ListGcvPengajuanBns extends ListRecords
{
    protected static string $resource = GcvPengajuanBnResource::class;

     protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Pencairan Dajam'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcvPengajuanBnStats::class,
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
        ->emptyStateDescription('Silakan buat data pengajuan BN')
        ->emptyStateHeading('Belum ada data pengajuan BN')
        ->emptyStateActions([
            Action::make('create')
                ->label('Buat Data Pengajuan BN')
                ->url($this->getResource()::getUrl('create'))
                ->icon('heroicon-m-plus')
                ->button(),
        ]);
}

}