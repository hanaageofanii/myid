<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekonsilResource\Pages;
use App\Filament\Resources\RekonsilResource\RelationManagers;
use App\Models\Rekonsil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekonsilResource extends Resource
{
    protected static ?string $model = Rekonsil::class;

    protected static ?string $title = "Input Transaksi Internal";
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Transaksi Internal";
    protected static ?string $navigationLabel = "Transaksi Internal";
    protected static ?string $pluralModelLabel = 'Daftar Transaksi Internal';
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
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
            'index' => Pages\ListRekonsils::route('/'),
            'create' => Pages\CreateRekonsil::route('/create'),
            'edit' => Pages\EditRekonsil::route('/{record}/edit'),
        ];
    }
}
