<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvVerifikasiDajamResource\Pages;
use App\Filament\Resources\GcvVerifikasiDajamResource\RelationManagers;
use App\Models\gcv_verifikasi_dajam;
use App\Models\GcvVerifikasiDajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GcvVerifikasiDajamResource extends Resource
{
    protected static ?string $model = gcv_verifikasi_dajam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvVerifikasiDajams::route('/'),
            'create' => Pages\CreateGcvVerifikasiDajam::route('/create'),
            'edit' => Pages\EditGcvVerifikasiDajam::route('/{record}/edit'),
        ];
    }
}