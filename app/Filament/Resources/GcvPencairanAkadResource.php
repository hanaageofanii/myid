<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPencairanAkadResource\Pages;
use App\Filament\Resources\GcvPencairanAkadResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\gcv_pencairan_akad;

class GcvPencairanAkadResource extends Resource
{
    protected static ?string $model = gcv_pencairan_akad::class;

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
            'index' => Pages\ListGcvPencairanAkads::route('/'),
            'create' => Pages\CreateGcvPencairanAkad::route('/create'),
            'edit' => Pages\EditGcvPencairanAkad::route('/{record}/edit'),
        ];
    }
}