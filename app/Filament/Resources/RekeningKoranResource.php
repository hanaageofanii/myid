<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekeningKoranResource\Pages;
use App\Filament\Resources\RekeningKoranResource\RelationManagers;
use App\Models\rekening_koran;
use App\Models\RekeningKoran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekeningKoranResource extends Resource
{
    protected static ?string $model = rekening_koran::class;

    protected static ?string $title = "Input Rekening Koran";
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Rekening Koran";
    protected static ?string $navigationLabel = "Rekening Koran";
    protected static ?string $pluralModelLabel = 'Daftar Rekening Koran';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
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
            'index' => Pages\ListRekeningKorans::route('/'),
            'create' => Pages\CreateRekeningKoran::route('/create'),
            'edit' => Pages\EditRekeningKoran::route('/{record}/edit'),
        ];
    }
}
