<?php

namespace App\Filament\Resources\GcvUangMukaResource\Pages;

use App\Filament\Resources\GcvUangMukaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GcvUangMukaResource\Widgets\gcv_uangMukaStats;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;


class ListGcvUangMukas extends ListRecords
{
    protected static string $resource = GcvUangMukaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Uang Muka'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            gcv_uangMukaStats::class,
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
