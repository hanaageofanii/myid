<?php

namespace App\Filament\Resources\FormLegalResource\Pages;

use App\Filament\Resources\FormLegalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormLegal extends EditRecord
{
    protected static string $resource = FormLegalResource::class;

    protected static ?string $title = "Ubah Data Sertifikat";

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus Data Sertifikat'),
        ];
    }

    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
        ->label('Simpan');
    }
    
}
