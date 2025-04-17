<?php

namespace App\Filament\Resources\CekPerjalananResource\Pages;

use App\Filament\Resources\CekPerjalananResource;
use App\Filament\Resources\CekPerjalananResource\Widgets\cek_perjalananStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCekPerjalanans extends ListRecords
{
    protected static string $resource = CekPerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Buat Data Cek Rekening & Transaksi'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            cek_perjalananStats::class,
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
