<?php

namespace App\Filament\Resources\FormKprResource\Pages;

use App\Filament\Resources\FormKprResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormKpr extends EditRecord
{

    protected static string $resource = FormKprResource::class;

    protected static ?string $title = "Ubah Data Penjualan";


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data'),
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
}
